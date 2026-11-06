# Nexi XPay REST Hosted Payment Page

Status: implemented for sandbox verification; **not production-ready**. No Nexi API key was configured, no interactive payment was made, and no payment-method activation or deployment was performed.

The integration uses Nexi's REST API with `X-Api-Key`. It does not use Classic Alias/MAC fields or the Classic DispatcherServlet. Payment method ID `2` remains inactive by default. PayPal, local pickup, free checkout, and the existing durable Nexi fulfillment workflow remain separate.

## API behavior and payment verification

1. Checkout displays a safe GET landing page. A customer-authorized POST locks the order, validates the current amount and fingerprint, and creates or reuses its single payment attempt.
2. The server calls `POST /orders/hpp` with JSON, `actionType: PAY`, and `captureType: IMPLICIT`, requesting immediate capture. Every REST call includes the environment's API key and a new UUID v4 `Correlation-Id`. HTTP calls have bounded timeouts and are not automatically retried.
3. The returned `orderId`, `hostedPage`, and `securityToken` are stored with the attempt. The hosted page URL is accepted only over HTTPS from a `nexigroup.com` host. Laravel's encrypted cast encrypts the security token at rest. The customer is redirected directly to Nexi's returned hosted page.
4. A repeated POST for an initialized attempt reuses the saved hosted page. If an initialization request has an uncertain outcome, the attempt is marked for review and cannot trigger another HPP request. Check Nexi's merchant portal before any manual recovery; do not make a new order/reference or retry until Nexi confirms the first request did not create an order. No provider idempotency guarantee is assumed.
5. Nexi notifications are JSON. The handler requires the matching `orderId` and `securityToken`, compares the token in constant time, then independently calls `GET /orders/{orderId}`. The notification body itself is not treated as payment evidence.
6. Only a matching order with an executed payment/capture operation for the exact expected integer-cent amount and EUR is recorded as paid. An authorization is recorded as `authorized`; it does not confirm or fulfill the order. The order's payment reference, amount, currency, current fingerprint, and allowed state transition are checked before fulfillment.
7. Pending or failed verification is never acknowledged as paid. Invalid tokens/references are rejected. A transient API/network/database failure returns an error rather than acknowledging the notification; Nexi's notification retry schedule must be confirmed for the merchant, so reconciliation is still required. Duplicate, delayed, and out-of-order notifications query current Nexi state; paid is never downgraded, and a later independently verified capture can advance an earlier authorization or negative result.
8. A browser return only displays database state. It cannot mark an order paid. Guest returns use a per-attempt capability and do not reveal the reusable order token.

The notification endpoint must be publicly reachable over HTTPS without login, browser challenge, or IP assumptions. Laravel's CSRF protection is excluded only for this server-to-server endpoint. Configure proxies and `APP_URL` so generated result, cancel, and notification URLs are public HTTPS URLs. Redact return-token query strings from proxy/access logs. Do not capture callback bodies or API headers in APM/debug tooling.

## Configuration

Use the secret manager or local environment file for each environment. **Do not paste keys in chat, commits, tickets, or logs.** This implementation does not read or modify the developer's `.env`.

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
- `compose.prod.yml` defaults to `APP_DEBUG=false`, a separate `shipping-dog-staging` database, log mail, empty external-service credentials, sandbox mode, and disabled external operations. Set staging secrets and verify those resolved settings before bringing up any publicly reachable instance.
- Do not log API keys or encrypted/decrypted security tokens. Rebuild Laravel's config cache after secret changes.

Production API-key availability and merchant enablement are not established by sandbox access. Obtain Nexi confirmation that the merchant account is enabled for the REST Hosted Payment Page product and production endpoint before considering production activation.

## Database migrations

The new migration adds the provider order reference, hosted page URL, encrypted notification token, and initialization state to the existing `nexi_payments` table. Existing fulfillment tables/records and the existing payment-attempt table are reused; no historical migration was edited. Review the migration plan against a restored copy before changing any non-test database.

The older migration `2026_11_05_145202_create_nexi_payments_table.php` also deletes zero-ID address/order-item/order rows and alters key columns. It must not be applied to an existing application database without a backup, orphan/zero-ID review, and explicit database-owner approval. Never run the test script against a development or production database; it refuses database names/users other than the isolated `shipping_dog_test`.

## Interactive sandbox verification

This requires a developer-provided sandbox API key and an approved public HTTPS staging URL. Do not use production inventory/credits for a sandbox test. Use a disposable staging database, a log mailer, disabled external operations, and only test orders.

1. Confirm Nexi sandbox API-key access for this merchant. From Nexi's official REST [API usage specifications](https://developer.nexi.it/it/api/specifiche-di-utilizzo-del-servizio) and merchant-specific sandbox onboarding material, obtain the current REST test-card number, expiry, CVV, expected outcome, and any 3-D Secure challenge credentials/instructions. On the hosted page, exercise a successful payment, decline/cancel, and both successful and unsuccessful 3DS challenges using only those documented REST sandbox values. Do not use real card data or Classic-terminal test cards. Nexi manages these values and challenge flows; this runbook intentionally does not copy values that may be changed or account-specific.
2. Set `APP_URL=https://your-approved-staging-host`, identify and set the exact `TRUSTED_PROXIES` source for the tunnel/proxy, provide `NEXI_SANDBOX_API_KEY`, keep `NEXI_SANDBOX=true`, and leave `NEXI_API_KEY` empty. Keep external operations disabled and configure a non-delivering mailer.
3. Review `php artisan migrate:status` and the pending migration list. Apply migrations only to the approved disposable staging database after its owner approves the schema change. Do not run `migrate:fresh` or reset a shared database.
4. Build configuration/routes/views as appropriate for the deployment. Enable payment method `2` only after explicit staging approval and verify the scheduler is running.
5. Complete one positive-value order as a guest and one as an authenticated customer. Confirm HPP initializes once, the browser goes to the returned hosted page, and the configured Nexi card/3DS challenge completes. Confirm a browser return alone leaves the order pending.
6. Inspect the provider notification and API status in redacted form. Confirm token validation and `GET /orders/{orderId}` agree on order reference, amount, EUR, and executed capture before the order reaches paid/confirmed. Also verify authorization-only, decline, cancel, delayed notification, duplicate notification, timeout, and altered-order cases.
7. Exercise duplicate POSTs and an ambiguous HPP timeout. Confirm no second HPP request is made automatically and that an uncertain attempt is reconciled with Nexi before further action.
8. With a pickup order and log mailer, explicitly enable external operations only if the staging operator approves; verify one confirmation email and no GLS request. Test shipped-order GLS behavior only in a separately approved GLS test setup.
9. Verify guest recap authorization, PayPal capture, pickup, and free checkout. Record sanitized outcomes and return payment method `2` to inactive until the next approved test.

The official [REST API references](https://developer.nexi.it/it/api/post-orders-hpp), [order verification reference](https://developer.nexi.it/it/api/get-orders-orderId), and [API usage specifications](https://developer.nexi.it/it/api/specifiche-di-utilizzo-del-servizio) are authoritative for current request/response fields, notification delivery, and sandbox procedures. The real merchant-specific response shape, card catalogue, and 3DS challenge behavior still require interactive confirmation.

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
