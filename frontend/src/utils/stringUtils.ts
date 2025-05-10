export function toKebabCase(str: string): string {
    return str
        .replace(/([a-z])([A-Z])/g, '$1-$2') // adding a hyphen between camelCase words
        .replace(/\s+/g, '-')                 // replacing spaces with hyphens
        .toLowerCase();                       // making everything lowercase
}

export function truncateString(input: string, maxLength: number): string {
    if (input.length <= maxLength) {
        return input;
    }
    return input.slice(0, maxLength - 3) + '...';
}



