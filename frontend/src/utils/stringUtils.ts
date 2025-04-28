export function toKebabCase(str: string): string {
    // Convert a string to kebab-case
    return str
        .replace(/([a-z])([A-Z])/g, '$1-$2') // add a hyphen between camelCase words
        .replace(/\s+/g, '-')                 // replace spaces with hyphens
        .toLowerCase();                       // make everything lowercase
}
