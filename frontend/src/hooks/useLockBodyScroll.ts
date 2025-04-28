import { useLayoutEffect } from 'react';

export function useLockBodyScroll(shouldLock: boolean) {
    useLayoutEffect(() => {
        if (!shouldLock) return;

        const originalOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        return () => {
            document.body.style.overflow = originalOverflow;
        };
    }, [shouldLock]);
}
