interface PriceReference {
  price: number;
  gain_w: number;
  gain_c: number;
  minimum_price: number;
}

interface ColorOption {
  id: number;
  name: string;
  primary: string;
  secondary: string;
}

export default function customProductEditor(
  references: Record<number, PriceReference>,
  types: Record<number, { colors: ColorOption[] }>,
  initialTypeId: number,
  initialPrice: number | string | null,
  initialColorIds: number[],
  initialDefaultColorId: number,
) {
  return {
    references,
    types,
    typeId: Number(initialTypeId),
    price: initialPrice,
    activeColorIds: initialColorIds.map(Number),
    defaultColorId: Number(initialDefaultColorId),
    gainW: "0.00",
    gainC: "0.00",
    minimumPrice: 0,
    pendingUploads: 0,
    saving: false,

    init() {
      if (this.price === null || this.price === "") {
        this.price = this.reference.price;
      }
      this.calculate();
    },

    get uploading() {
      return this.pendingUploads > 0;
    },

    startUpload() {
      this.pendingUploads++;
    },

    finishUpload() {
      this.pendingUploads = Math.max(0, this.pendingUploads - 1);
    },

    get colors() {
      return this.types[this.typeId]?.colors ?? [];
    },

    get activeColors() {
      return this.colors.filter((color) => this.activeColorIds.includes(Number(color.id)));
    },

    get reference() {
      return this.references[this.typeId] ?? { price: 0, gain_w: 0, gain_c: 0, minimum_price: 0 };
    },

    changeType() {
      this.price = this.reference.price;
      const availableIds = this.colors.map((color) => Number(color.id));
      this.activeColorIds = this.activeColorIds.filter((id) => availableIds.includes(id));
      this.changeColors();
      this.calculate();
    },

    changeColors() {
      this.activeColorIds = this.activeColorIds.map(Number);
      if (!this.activeColorIds.includes(this.defaultColorId)) {
        this.defaultColorId = this.activeColorIds[0] ?? 0;
      }
      this.$dispatch("custom-product-variants-updated", {
        typeId: this.typeId,
        colorIds: this.activeColorIds,
      });
    },

    calculate() {
      const price = Math.max(0, this.parseNumber(this.price));
      this.minimumPrice = this.parseNumber(this.reference.minimum_price);
      const baseW = this.reference.price - this.reference.gain_w / 0.75;
      const baseC = this.reference.price - this.reference.gain_c / 0.75;
      this.gainW = Math.max(0, (price - baseW) * 0.75).toFixed(2);
      this.gainC = Math.max(0, (price - baseC) * 0.75).toFixed(2);
    },

    normalizePrice() {
      this.price = Math.max(this.minimumPrice, this.parseNumber(this.price)).toFixed(2);
      this.calculate();
    },

    parseNumber(value: unknown) {
      const number = Number.parseFloat(String(value ?? "").replace(",", "."));
      return Number.isFinite(number) ? number : 0;
    },
  };
}
