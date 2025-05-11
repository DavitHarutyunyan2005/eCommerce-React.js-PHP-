import React from 'react';
import { CartItem } from './CartItem';
import { useCart } from '../../context/CartContext';
import { useLockBodyScroll } from '../../hooks/useLockBodyScroll';
import { useInsertOrder } from '../../graphql/mutations/insertOrder';


export const CartOverlay: React.FC = () => {

    const { cart, totalPrice, isCartOpen, clearCart } = useCart();
    // hide body scroll when cart is open
    useLockBodyScroll(isCartOpen);

    const [ insertOrder ] = useInsertOrder();

    if (!isCartOpen) return null;

    const handleInsertOrder = async () => {
        try {

            const { errors } = await insertOrder({
                variables: {
                    products: cart.map((product) => ({
                        productId: product.id,
                        priceId: product.prices[0].id,
                        attributeItemIds: product.attributes.map(
                            (attr) => attr.items.find((item) => item.selected)?.id || ''
                        ),
                        quantity: product.quantity,
                    })),
                },
            });
    
            if (errors && errors.length > 0) {
                console.error("GraphQL errors:", errors);
                return;
            }

            clearCart();
        } catch (err) {
            console.error("Network or unexpected error while inserting order:", err);
        }
    };
    

    return (
        <div className="bg-white  mx-auto p-4 shadow-md scrollbar-hide">
            <h1 className="text-xl font-semibold font-raleway mb-2">
                <span className="font-bold">My Bag,</span> <span className='font-light'>{cart.length > 1 ? `${cart.length} items` : `${cart.length} item`}</span>
            </h1>

            {cart.map((product, index) => (
                <CartItem product={product} key={index} />
            ))}

            <div
                className="flex justify-between font-semibold text-lg mt-4"
                data-testid='cart-total'
            >
                <span>Total</span>
                <span className='font-raleway'>${totalPrice.toFixed(2)}</span>
            </div>

            <div className="relative">
                <button
                    disabled={cart.length === 0}
                    onClick={handleInsertOrder}
                    className={
                        'py-3 px-6 font-raleway text-white w-full ' +
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
