// components/CartModal.tsx
import React from 'react';
import { CartOverlay } from './CartOverlay';

type CartModalProps = {
    isOpen: boolean;
    onClose: () => void;
};

export const CartModal: React.FC<CartModalProps> = ({ isOpen, onClose }) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex justify-end">


            {/* Cart panel */}
            <div className="relative w-full max-w-sm h-full bg-white shadow-lg p-4 overflow-y-auto">
                <CartOverlay />
            </div>
        </div>
    );
};
