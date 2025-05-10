// src/utils/sortSizes.ts

const clothingSizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

export function sortSizes(sizes: string[]): string[] {
    return sizes.slice().sort((a, b) => {
        const aIsNumber = !isNaN(Number(a));
        const bIsNumber = !isNaN(Number(b));

        // If both are numbers
        if (aIsNumber && bIsNumber) {
            return Number(a) - Number(b);
        }

        // If both are clothing sizes
        if (!aIsNumber && !bIsNumber) {
            const indexA = clothingSizeOrder.indexOf(a.toUpperCase());
            const indexB = clothingSizeOrder.indexOf(b.toUpperCase());
            return indexA - indexB;
        }

        return aIsNumber ? -1 : 1;
    });
}
