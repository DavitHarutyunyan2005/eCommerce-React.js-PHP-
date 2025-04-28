import React from 'react';
import { CartItem } from './CartItem';
import { useCart } from '../../context/CartContext';
import { useLockBodyScroll } from '../../hooks/useLockBodyScroll';


export const CartOverlay: React.FC = () => {

    const { cart, totalPrice, isCartOpen } = useCart();
    // hide body scroll when cart is open
    useLockBodyScroll(isCartOpen);

    if (!isCartOpen) return null;

    return (
        <div className="bg-white  mx-auto p-4 shadow-md  scrollbar-hide">
            <h1 className="text-xl font-semibold mb-2">
                <span className="font-bold">My Bag</span>, {cart.length > 1 ? `${cart.length} items` : `${cart.length} item`}
            </h1>

            {cart.map((product, index) => (
                <CartItem product={product} key={index} />
            ))}

            <div
                className="flex justify-between font-semibold text-lg mt-4"
                data-testid='cart-total'
            >
                <span>Total</span>
                <span>${totalPrice.toFixed(2)}</span>
            </div>

            <div className="relative">
                <button
                    disabled={cart.length === 0}
                    // onClick={()=>addToCart(product)}
                    className={
                        'py-3 px-6 font-semibold text-white w-full ' +
                        'bg-[#5ECE7B] hover:bg-green-600 cursor-pointer'
                    }
                >
                    PLACE ORDER
                </button>
                {cart.length === 0 && (
                    <div className="absolute top-0 left-0 w-full h-full bg-[#40424A] opacity-45 cursor-not-allowed"></div>
                )}
            </div>
        </div>
    );
};
