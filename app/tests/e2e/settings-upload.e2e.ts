import { expect, test, type Page } from "@playwright/test";
import { E2E_EMAIL } from "./helpers";

// 1x1 transparent PNG
const PNG = Buffer.from(
  "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==",
  "base64",
);

async function openSettings(page: Page) {
  await page.goto("/login");
  await page.getByPlaceholder("Indirizzo e-mail").fill(E2E_EMAIL);
  await page.getByPlaceholder("Password").fill(process.env.E2E_PASSWORD!);
  await page.getByRole("button", { name: "Log In" }).click();
  await page.waitForURL("**/user-dashboard");
  await page.goto("/user-dashboard/settings");
}

// The first uploader is the profile icon, the second one the cover
const fileInput = (page: Page, index: number) => page.locator('input[type="file"]').nth(index);

test.beforeEach(async ({ page }) => {
  await openSettings(page);
});

test("a valid image is uploaded without errors and updates the header avatar", async ({ page }) => {
  const headerIcon = page.locator("section.breakout img[alt='User icon']");
  const before = await headerIcon.getAttribute("src");

  await fileInput(page, 0).setInputFiles({ name: "icon.png", mimeType: "image/png", buffer: PNG });

  await expect(headerIcon).not.toHaveAttribute("src", before ?? "");
  await expect(page.locator("[x-show='message']")).toBeHidden();
});

test("a file that is not an image shows an error alert", async ({ page }) => {
  await fileInput(page, 0).setInputFiles({
    name: "document.pdf",
    mimeType: "application/pdf",
    buffer: Buffer.from("%PDF-1.4 not an image"),
  });

  await expect(page.locator(".alert", { hasText: "non è un'immagine valida" })).toBeVisible();
});

test("an image heavier than 32 MB shows an error alert and keeps the current image", async ({
  page,
}) => {
  const headerIcon = page.locator("section.breakout img[alt='User icon']");
  const before = await headerIcon.getAttribute("src");

  await fileInput(page, 0).setInputFiles({
    name: "huge.png",
    mimeType: "image/png",
    buffer: Buffer.concat([PNG, Buffer.alloc(33 * 1024 * 1024)]),
  });

  await expect(page.locator(".alert:visible")).toContainText(/32 MB/);
  await expect(headerIcon).toHaveAttribute("src", before ?? "");
});

test("the error alert disappears when a new upload starts", async ({ page }) => {
  await fileInput(page, 1).setInputFiles({
    name: "x.txt",
    mimeType: "text/plain",
    buffer: Buffer.from("x"),
  });
  await expect(page.locator(".alert:visible")).toBeVisible();

  await fileInput(page, 1).setInputFiles({ name: "cover.png", mimeType: "image/png", buffer: PNG });

  await expect(page.locator(".alert:visible")).toHaveCount(0);
});
