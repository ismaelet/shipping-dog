import Sortable from "sortablejs";

interface PortfolioWire {
  saveOrder: (type: string, ids: number[]) => Promise<unknown>;
}

export default function portfolioSorter() {
  return {
    saving: false,
    saved: false,
    error: false,

    setup(root: HTMLElement, wire: PortfolioWire) {
      root.querySelectorAll<HTMLElement>(".js-sortable").forEach((gallery) => {
        const sortable = new Sortable(gallery, {
          animation: 150,
          draggable: "article",
          handle: ".js-drag-handle",

          onEnd: async (event) => {
            if (event.oldIndex === event.newIndex) return;

            const ids = Array.from(
              gallery.querySelectorAll<HTMLElement>("article[data-id]"),
              (card) => Number(card.dataset.id),
            );

            this.saving = true;
            this.saved = false;
            this.error = false;

            sortable.option("disabled", true);

            try {
              await wire.saveOrder(gallery.dataset.type ?? "", ids);
              this.saved = true;
            } catch (error) {
              this.error = true;
              console.error("Portfolio sorting failed:", error);
            } finally {
              this.saving = false;
              sortable.option("disabled", false);
            }
          },
        });
      });
    },
  };
}
