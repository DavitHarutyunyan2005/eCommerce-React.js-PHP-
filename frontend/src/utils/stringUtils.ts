export function toKebabCase(str: string): string {
    return str
        .replace(/\s+/g, '-')                 // replacing spaces with hyphens
        .toLowerCase();                       // making everything lowercase
}

export function truncateString(input: string, maxLength: number): string {
    if (input.length <= maxLength) {
        return input;
    }
    return input.slice(0, maxLength - 3) + '...';
}



