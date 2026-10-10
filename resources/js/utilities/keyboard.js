export function nextIndex(current, length, direction = 1) {
    if (length === 0) return -1;

    return (current + direction + length) % length;
}
