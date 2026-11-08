# Nexi XPay REST Hosted Payment Page

Status: local sandbox tooling and REST contract tests are implemented. Real REST sandbox payments were validated on 2026-11-08 using Nexi's published implicit-capture test terminal; see the evidence below. **Not production-ready**; merchant-specific and production access remain unverified.

The integration uses Nexi's REST API with `X-Api-Key`. It does not use Classic Alias/MAC fields or the Classic DispatcherServlet. Payment method ID `2` remains inactive by default. PayPal, local pickup, free checkout, and the existing durable Nexi fulfillment workflow remain separate.

## API behavior and payment verification

1. Checkout displays a safe GET landing page. A customer-authorized POST locks the order, validates the current amount and fingerprint, and creates or reuses its single payment attempt.
2. The server calls `POST /orders/hpp` with nested `order` and `paymentSession` JSON objects (amount in both), `actionType: PAY`, and `captureType: IMPLICIT`, requesting immediate capture. Every REST call includes the environment's API key and a new UUID v4 `Correlation-Id`. HTTP calls have bounded timeouts and are not automatically retried.
3. The submitted merchant `orderId` and returned `hostedPage` and `securityToken` are stored with the attempt. The documented response does not echo the order reference. The hosted page URL is accepted only over HTTPS from a `nexigroup.com` host. Laravel's encrypted cast encrypts the security token at rest. The customer is redirected directly to Nexi's returned hosted page.
4. A repeated POST for an initialized attempt reuses the saved hosted page. If an initialization request has an uncertain outcome, the attempt is marked for review and cannot trigger another HPP request. Check Nexi's merchant portal before any manual recovery; do not make a new order/reference or retry until Nexi confirms the first request did not create an order. No provider idempotency guarantee is assumed.
5. Nexi notifications are JSON. The handler requires the matching `operation.orderId` and top-level `securityToken`, compares the token in constant time, then independently calls `GET /orders/{orderId}`. The notification body itself is not treated as payment evidence.
6. Only a matching order with an executed payment/capture operation for the exact expected integer-cent amount and EUR is recorded as paid. An `AUTHORIZATION` with result `AUTHORIZED` is recorded as `authorized`; an `AUTHORIZATION` with result `EXECUTED` represents an implicitly captured payment. Amount/currency come from `operationAmount` and `operationCurrency`, and each operation must match the merchant reference. Authorization alone does not confirm or fulfill the order. The order's payment reference, amount, currency, current fingerprint, and allowed state transition are checked before fulfillment.
7. Pending or failed verification is never acknowledged as paid. Invalid tokens/references are rejected. A transient API/network/database failure returns an error rather than acknowledging the notification; Nexi's notification retry schedule must be confirmed for the merchant, so reconciliation is still required. Duplicate, delayed, and out-of-order notifications query current Nexi state; paid is never downgraded, and a later independently verified capture can advance an earlier authorization or negative result.
8. A browser return only displays database state. It cannot mark an order paid. Guest returns use a per-attempt capability and do not reveal the reusable order token.

The notification endpoint must be publicly reachable over HTTPS without login, browser challenge, or IP assumptions. Laravel's CSRF protection is excluded only for this server-to-server endpoint. Configure proxies and `APP_URL` so generated result, cancel, and notification URLs are public HTTPS URLs. Redact return-token query strings from proxy/access logs. Do not capture callback bodies or API headers in APM/debug tooling.

## Configuration

Use the secret manager or local environment file for each environment. **Do not paste keys in chat, commits, tickets, or logs.** The standalone sandbox uses its own `.env.nexi-sandbox`; the normal developer `.env` is not required or modified.

```dotenv
APP_URL=https://your-approved-staging-host
TRUSTED_PROXIES=
NEXI_SANDBOX=true
NEXI_SANDBOX_API_KEY=
NEXI_API_KEY=
NEXI_EXTERNAL_OPERATIONS_ENABLED=false
```

- With `NEXI_SANDBOX=true`, `NEXI_SANDBOX_API_KEY` is selected and the base URL is `https://xpaysandbox.nexigroup.com/api/phoenix-0.0/psp/api/v1`.
- With `NEXI_SANDBOX=false`, `NEXI_API_KEY` is selected and the base URL is `https://xpay.nexigroup.com/api/phoenix-0.0/psp/api/v1`.
- `NEXI_EXTERNAL_OPERATIONS_ENABLED=false` keeps confirmation email and GLS work durably pending. It is separate from the sandbox switch; enabling it can contact real email and GLS services.
- Keep payment method ID `2` inactive until an approved sandbox setup is ready. API-key configuration alone does not activate checkout.
- Configure `TRUSTED_PROXIES` with the exact immediate proxy address(es) that connect to Laravel, so forwarded HTTPS is trusted only from that proxy. Do not use `*` for a public origin. `APP_URL` must be the public HTTPS host; generated callback URLs are not taken from local `localhost` URLs.
- `compose.nexi-sandbox.yml` is standalone: separate image, database and storage volumes, log mail, empty production credentials, debug disabled, and external operations disabled. It does not inherit `compose.yml`.
- Do not log API keys or encrypted/decrypted security tokens. Rebuild Laravel's config cache after secret changes.

Production API-key availability and merchant enablement are not established by sandbox access. Obtain Nexi confirmation that the merchant account is enabled for the REST Hosted Payment Page product and production endpoint before considering production activation.

## Database migrations

The new migration adds the provider order reference, hosted page URL, encrypted notification token, and initialization state to the existing `nexi_payments` table. Existing fulfillment tables/records and the existing payment-attempt table are reused; no historical migration was edited. Review the migration plan against a restored copy before changing any non-test database.

The older migration `2026_11_05_145202_create_nexi_payments_table.php` also deletes zero-ID address/order-item/order rows and alters key columns. It must not be applied to an existing application database without a backup, orphan/zero-ID review, and explicit database-owner approval. Never run the test script against a development or production database; it refuses database names/users other than the isolated `shipping_dog_test`.

## Testing with the existing development database

The standalone sandbox below is safer. To use the normal development checkout instead, obtain database-owner approval for any pending migrations, use only test orders and data, and approve public exposure of the entire development app. Sandbox confirmation still modifies orders, inventory and seller credits in that database. The Nexi payment method must be explicitly enabled and the sandbox API key configured in the normal Compose environment.

The `compose.nexi-dev.yml` overlay disables debug, email delivery, GLS, PayPal and Google credentials and Nexi external operations; it also requires secure session cookies. Never expose the regular development configuration publicly. The overlay still uses the normal development database and existing Vite server, so browse from the development machine with Vite available on `localhost:5173`.

From the repository root, first start the app with the safety overlay, then start the tunnel and obtain its HTTPS URL and exact proxy IP:

```bash
docker compose -f compose.yml -f compose.nexi-dev.yml up -d --no-deps app
docker compose -f compose.yml -f compose.nexi-dev.yml --profile nexi-dev up -d --no-deps nexi-tunnel
docker compose -f compose.yml -f compose.nexi-dev.yml logs nexi-tunnel
docker inspect shipping-dog-nexi-tunnel-1 --format '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
```

In the same shell, set `NEXI_DEV_PUBLIC_URL` to the tunnel's HTTPS URL and `NEXI_DEV_TRUSTED_PROXY` to its IP address. Then recreate the app and start the scheduler:

```bash
export NEXI_DEV_PUBLIC_URL=https://your-tunnel.trycloudflare.com
export NEXI_DEV_TRUSTED_PROXY=the-tunnel-ip
docker compose -f compose.yml -f compose.nexi-dev.yml up -d --no-deps app
docker compose -f compose.yml -f compose.nexi-dev.yml --profile nexi-dev up -d --no-deps nexi-scheduler
```

Verify public `/up` and that an empty JSON POST to `/services/nexi/notification` returns 403 before testing. Browse only through the HTTPS tunnel hostname, create a fresh test order, and use Nexi's documented sandbox cards. The scheduler processes verified payments; email and shipment operations remain pending. A restarted tunnel can change both URL and IP: update both values and recreate the app and scheduler before new attempts. Reconcile existing attempts rather than initializing them again.

To end testing, stop the tunnel and scheduler before restoring the ordinary development configuration:

```bash
docker compose -f compose.yml -f compose.nexi-dev.yml --profile nexi-dev stop nexi-tunnel nexi-scheduler
docker compose -f compose.yml up -d --no-deps app
```

Do not restore the normal app while the public tunnel is running: normal development settings can include debug mode and real-service credentials. Existing HPP sessions retain their original callback URL; reconcile in-flight payments before stopping the tunnel.

## Local sandbox setup

Run repository-level commands from the repository root. Docker Compose must be available; this setup uses loopback port 8001 and subnet `172.30.89.0/24`. Do not run it with the normal Compose file or add `-p shipping-dog`. The separate database is `shipping_dog_nexi_sandbox`; the automated Pest database remains `shipping_dog_test`.

1. Create a private environment file and separate credentials:

```bash
cp .env.nexi-sandbox.example .env.nexi-sandbox
chmod 600 .env.nexi-sandbox
php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'
php -r 'echo bin2hex(random_bytes(24)).PHP_EOL;'
```

Put the first generated value in `NEXI_SANDBOX_APP_KEY` and the second in `NEXI_SANDBOX_DB_PASSWORD`. Keep the application key stable while payment tokens exist. Set `NEXI_SANDBOX_API_KEY` from Nexi's official [Test Area API keys](https://developer.nexi.it/en/area-test/api-key), choosing the implicit-capture terminal for your activation path. Published test keys are sandbox-only; a merchant-specific key may also be used. Leave production credentials empty. Never use a real card.

2. Define a helper in the current shell and build the isolated image. Its Dockerfile runs `pnpm run build`, excludes local secrets/caches/storage and `public/hot`, and includes the MariaDB client needed to load the schema dump.

```bash
sandbox() { docker compose --env-file .env.nexi-sandbox -f compose.nexi-sandbox.yml "$@"; }
sandbox config --quiet
sandbox build app
sandbox up -d db app
sandbox exec app php artisan migrate --force --no-interaction
sandbox up -d tunnel
sandbox logs tunnel
```

The historical migration that deletes zero-ID rows runs only on this disposable database. Do not migrate the normal development database for this procedure. If bootstrap fails, inspect the error before continuing; never substitute `migrate:fresh` against another database.

3. Copy the `https://….trycloudflare.com` hostname printed by the tunnel into `NEXI_SANDBOX_URL` in `.env.nexi-sandbox`. Keep the tunnel running. Recreate only the application so it picks up the URL, then create fixtures and start processing:

```bash
sandbox up -d --no-deps app
sandbox exec app php artisan db:seed --class=NexiSandboxSeeder --force
sandbox exec app php artisan nexi:sandbox-preflight
sandbox --profile processing up -d scheduler
sandbox exec scheduler php artisan schedule:list
```

The seeder prints a signed guest link (valid for two hours), a synthetic customer login with a random password, and the authenticated order's payment URL. Each run creates two fresh EUR 12.34 orders and rotates the synthetic customer's password, preserving earlier payment attempts. The guest entry route requires a valid signature, local sandbox mode and the exact disposable database; it returns 404 elsewhere. The fixtures intentionally contain minimal order items for payment/confirmation testing; they are not a complete storefront catalogue or a test of product recap rendering.

Preflight checks local isolation, configuration, migrations, Nexi activation, secure sessions, callback URL generation and built assets. It prints labels only and never contacts Nexi. It cannot establish external reachability or scheduler liveness. From another network, check `GET /up` and POST `{}` as JSON to `/services/nexi/notification`; expect 403 from the application, without a login, CSRF rejection, or Cloudflare challenge. Do not send valid payment tokens as diagnostic probes.

Cloudflare [Quick Tunnels](https://developers.cloudflare.com/tunnel/get-started/quick-tunnels/) need no account and keep the same URL for the life of the tunnel process. Restarting/recreating the tunnel changes that URL: update the environment and recreate app/scheduler before creating new attempts. Existing HPP sessions retain their original callbacks; reconcile those rather than reinitializing them. The fixed tunnel IP is the only trusted proxy. If the subnet conflicts locally, change the subnet, tunnel IP, trusted-proxy setting, and preflight expectation together. No Cloudflare Access login or email gate may cover the callback.

## Interactive sandbox acceptance

Browse exclusively through the tunnel HTTPS hostname, including login. Open the guest link signed out; log in as the generated customer in a separate browser context and open the printed payment URL. Do not switch between localhost and the public hostname mid-checkout.

After success, failure or abort, Nexi may show its own outcome page; click **Back to the shop** to follow the configured merchant return URL. Aborting also requires confirming **Exit** in Nexi's dialog. A hosted outcome page is not an automatic browser redirect.

Use current [REST sandbox cards](https://developer.nexi.it/en/area-test/carte-di-pagamento) and the hosted 3DS simulator. Test success, authentication failure and cancellation with separate fresh orders. Nexi documents `EXECUTED` for implicit capture, `AUTHORIZED` for explicit capture and `THREEDS_FAILED` for unsuccessful authentication. Declines, authorization-only, mismatched payloads and ambiguous timeouts also have deterministic mocked coverage; do not assume every outcome is selectable on every sandbox terminal.

For each guest/customer success, verify:

- Checkout POST initializes HPP once; repeated POSTs reuse the session.
- Browser return displays durable state and cannot mark an order paid.
- A valid notification triggers independent `GET /orders/{orderId}` verification of the operation reference, amount, currency and capture result.
- The scheduler confirms the order once. With external operations disabled, email and shipment operations remain pending, and fulfillment remains `confirmed` rather than `fulfilled`.
- Failed/cancelled/authentication-only attempts do not confirm the order. Create another fixture for another payment; do not edit or reset attempts.
- Duplicate or out-of-order notifications do not repeat confirmation or downgrade a paid payment. Tokens and request bodies must not be included in logs or evidence.

For a missed-notification exercise, stop the tunnel after HPP initialization, complete the payment, then query the durable attempt using the same merchant reference:

```bash
sandbox exec app php artisan nexi:reconcile-payment PAYMENT_ID
sandbox exec app php artisan nexi:process --payment=PAYMENT_ID
```

Do not assume notification retries: Nexi documents that a notification failure does not cancel the transaction. Restarting the Quick Tunnel creates a new public URL; update/recreate app and scheduler before further tests. Never retry an ambiguous HPP initialization automatically; check its merchant reference in Nexi Back Office first.

Record date, guest/customer scenario, local payment ID, expected/observed state, and confirmation/operation counts in sanitized evidence. Keep tokens, full hosted URLs and credentials out of reports. Interactive verification is incomplete until actual outcomes are recorded. Real GLS/email execution is outside this sandbox's acceptance criteria.

Stop all sandbox services while retaining data:

```bash
sandbox --profile processing down
```

To discard **only this sandbox's** database and storage after reconciling attempts:

```bash
sandbox --profile processing down --volumes
```

Rebuild the image after source changes (`sandbox build app`), then recreate app/scheduler without recreating the tunnel. Existing database/storage volumes persist. API/route references: [HPP](https://developer.nexi.it/en/api/post-orders-hpp), [order verification](https://developer.nexi.it/en/api/get-orders-orderId), [notifications](https://developer.nexi.it/en/api/notifica).

## Recorded sandbox validation — 2026-11-08

These results used the standalone Compose environment, a Cloudflare Quick Tunnel, Chromium, Nexi's published implicit-capture terminal and a published REST sandbox VISA card. No real card or production merchant credentials were used. IDs below belong only to the disposable sandbox database.

| Scenario                                                         | Observed result                                                                                                                                                                                                                                             |
| ---------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Build and initial database bootstrap                             | Image built with `pnpm run build`; schema dump loaded and all pending migrations applied.                                                                                                                                                                   |
| Readiness and public access                                      | All 13 preflight checks passed; public `/up` returned 200; unsigned JSON notification returned 403 without login/CSRF interception.                                                                                                                         |
| Guest success — payment 1, order 1                               | Real HPP accepted EUR 12.34; successful 3DS; actual callback independently verified payment; scheduler advanced fulfillment to `confirmed`. Merchant return displayed payment received.                                                                     |
| Duplicate notifications — payment 1                              | Two internal replays, each independently querying real sandbox state, returned 200. Reconfirming left exactly two durable operations for the order.                                                                                                         |
| Authenticated success / missed notification — payment 2, order 2 | Logged in through the public hostname. Stopped the tunnel before successful 3DS; local payment stayed `pending` despite Nexi's success page. Reconciliation returned `paid`; processing advanced fulfillment to `confirmed` without another initialization. |
| Unsuccessful 3DS — payment 3, order 3                            | Nexi showed payment error; local payment became `failed`; order stayed received/unconfirmed. Merchant return displayed payment not completed.                                                                                                               |
| Abort — payment 4, order 5                                       | Confirmed Exit; Nexi showed payment cancelled. Independent reconciliation returned `cancelled`; order stayed received/unconfirmed. Back to the shop reached the merchant's cancelled result.                                                                |
| External operations                                              | Exactly one email and one shipment operation per successful order, all `pending`. Failed and cancelled orders created no fulfillment operations.                                                                                                            |
| Automated validation                                             | 80 targeted Nexi tests passed with 284 assertions, including concurrency and existing checkout regressions. PHP formatting, Blade compilation and Compose/document formatting checks passed.                                                                |

Automated coverage additionally checks uncertain HPP initialization, wrong references/amounts/currencies, authorization-only and unknown results, forged/expired fixture links, database-isolation guards, and proxy-generated HTTPS URLs. Those scenarios were not all reproduced against the shared live sandbox terminal. Product catalogue/recap rendering, merchant-specific terminal behavior, production credentials and real email/GLS execution remain outside this validation.

The sandbox services were stopped after verification; its image, named volumes and private `.env.nexi-sandbox` remain available. A restarted Quick Tunnel gets a new hostname: update the environment and recreate app/scheduler before generating fresh fixture links. Existing payment references and encrypted tokens must be preserved.

## Recovery and reconciliation

- `php artisan nexi:process` serially confirms durable, verified captured payments and scans pending email/GLS operations. It runs every minute through the Laravel scheduler; the scheduler must be running in every environment where Nexi attempts can remain open.
- `php artisan nexi:process --payment=ID` processes one durable payment and its order operations.
- `php artisan nexi:reconcile-payment ID` reads the provider's current order status and updates only the matching durable attempt. It never creates a new HPP session, charges, or starts fulfillment; run `nexi:process` separately after a verified capture.
- `php artisan nexi:reconcile-operation ID done --verified` marks an uncertain external operation completed after confirming the outcome.
- `php artisan nexi:reconcile-operation ID retry --verified` permits a retry only after confirming the external action did not occur and stopping any worker that could still be processing it.
- For an uncertain HPP initialization, first check the exact merchant order reference in Nexi Back Office; do not retry initialization or create a new reference while the outcome is uncertain. For a missed notification, use `nexi:reconcile-payment ID` to verify the provider's current state.
- Database confirmation, seller-credit changes, inventory changes, coupon consumption, and durable operation records commit under the existing order lock. Email and GLS operations are claimed durably before network I/O. Uncertain external outcomes go to review and are not automatically resent. This deliberately does not claim exactly-once delivery for external email/GLS services.
- Monitor pending, failed, `review`, and stale `processing` records. The processor logs record IDs and exception classes only; operational alerting and an operator response path must be configured separately.

## Production deployment and rollback

Production deployment, Nexi activation, and live payments require explicit approval and are not performed by this change.

Before any production rollout:

1. Complete successful interactive REST sandbox payments including 3DS, independent API verification, notification retries, authorization-vs-capture, fulfillment, and reconciliation.
2. Get Nexi confirmation that this merchant has production REST/HPP access, production API keys, immediate capture support for the terminal, the current notification contract, and current test/3DS procedures. Sandbox credentials do not establish production access.
3. Review the release and all pending migrations against a restored production backup. Obtain database-owner approval before running any migration, particularly the historical migration described above.
4. Obtain public HTTPS callback readiness, set production `APP_URL`, install the production `NEXI_API_KEY` in the secret manager, set `NEXI_SANDBOX=false`, and verify API/log redaction. Keep method `2` inactive and external operations disabled until separate production activation and deployment approvals are granted.
5. Ensure the scheduler and monitoring are active. Review email/GLS destinations and the operational reconciliation process before any explicit approval to enable external operations.

Rollback means deactivate payment method `2` to stop new checkout attempts while leaving callbacks and reconciliation available for existing attempts. Stop new external operations if needed, reconcile in-flight work, and roll back application code only with a schema-compatible plan. Do not drop payment/operation tables or delete provider references, tokens, or acknowledged attempts as a rollback shortcut.

## Automated validation and readiness

The Pest suite mocks Nexi HTTP calls and uses only the isolated `shipping_dog_test` database. Coverage includes request bodies/headers and UUID correlation IDs, sandbox/production endpoint selection, HPP failures and malformed responses, uncertain initialization without retries, encrypted tokens, JSON notifications, independent order verification, amount/currency/reference checks, authorization versus capture, failed/cancelled and out-of-order states, duplicate initialization/notifications, guest returns, durable fulfillment/reconciliation, and PayPal/pickup/free-checkout regression paths.

Automated tests cannot verify Nexi's real sandbox request acceptance, actual callback payload/notification-token contract, real 3DS flows, terminal capture configuration, provider retry timing, settlement, production API-key access, or live fulfillment. Do not claim production readiness until interactive sandbox verification succeeds and Nexi confirms production REST availability.
