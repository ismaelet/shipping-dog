export default function portfolioUpload(initialStep = 1) {
  return {
    step: initialStep,
    preview: null as string | null,
    invalid: null as string | null,
    dragging: false,
    uploading: false,
    allowed: ["png", "jpg", "jpeg", "webp"],

    pick(input: HTMLInputElement) {
      const file = input.files?.[0];
      if (!file) return;

      const extension = file.name.split(".").pop()?.toLowerCase() ?? "";

      if (!this.allowed.includes(extension)) {
        input.value = "";
        this.invalid = extension.toUpperCase();
        return;
      }

      this.invalid = null;

      if (this.preview) {
        URL.revokeObjectURL(this.preview);
      }

      this.preview = URL.createObjectURL(file);
      this.step = 2;
    },

    drop(event: DragEvent, input: HTMLInputElement) {
      if (!event.dataTransfer) return;

      input.files = event.dataTransfer.files;
      this.pick(input);
    },

    reset(input: HTMLInputElement) {
      input.value = "";

      if (this.preview) {
        URL.revokeObjectURL(this.preview);
      }

      this.preview = null;
      this.invalid = null;
      this.step = 1;
    },
  };
}
