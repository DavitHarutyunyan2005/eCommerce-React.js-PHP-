import React from 'react';
import { OrderProduct } from '../../types/OrderProductType';
import { useCart } from '../../context/CartContext';
import clsx from 'clsx';
import { toKebabCase, truncateString } from '../../utils/stringUtils';

interface CartItemProps {
    product: OrderProduct;
}

export const CartItem: React.FC<CartItemProps> = ({ product }) => {

    const { addToCart, removeFromCart } = useCart();

    return (
        <div className="flex justify-between gap-2 border-b p-2">
            {/* DETAILS */}
            <div className='flex flex-col justify-between '>
                <h2 className="text-lg font-raleway">{truncateString(product.name, 20)}</h2>
                <p className="text-md font-bold font-raleway">${product.prices[0].amount.toFixed(2)}</p>

                {/* ATTRIBUTES (color, size, etc.) */}
                <div>
                    {product.attributes.map((attr, index) => {
                        if (attr.type === 'text') {
                            return (
                                <div
                                    className="flex flex-col gap-1"
                                    key={index}
                                    data-testid={`cart-item-attribute-${toKebabCase(attr.name)}`}
                                >
                                    <span className='font-raleway'>{attr.name}:</span>
                                    <div className=''>
                                        {attr.items.map((item, i) => (
                                            <button
                                                key={i}
                                                className={clsx('px-2 py-1 border text-sm mr-0.5', item.selected && 'bg-black text-white')}
                                                data-testid={
                                                    `cart-item-attribute-${toKebabCase(attr.name)}-${toKebabCase(item.displayValue)}`
                                                    + (item.selected ? '-selected' : '')
                                                }                                            >
                                                {item.value}
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )
                        }
                        if (attr.type === 'swatch') {
                            return (
                                <div
                                    className="flex flex-col gap-2"
                                    key={index}
                                    data-testid={`cart-item-attribute-${toKebabCase(attr.name)}`}
                                >
                                    <span className='font-raleway'>Color:</span>
                                    <div className='flex gap-2'>
                                        {attr.items.map((item, i) => (
                                            <div
                                                key={i}
                                                className={clsx('w-6 h-6 border', item.selected && 'ring-2 ring-black')}
                                                style={{ backgroundColor: item.value }}
                                                data-testid={
                                                    `cart-item-attribute-${toKebabCase(attr.name)}-${toKebabCase(item.displayValue)}`
                                                    + (item.selected ? '-selected' : '')
                                                }
                                            />
                                        ))}
                                    </div>
                                </div>
                            )
                        }
                    })}
                </div>
            </div>
            {/* QUANTITY */}
            <div className="flex flex-col items-center justify-between gap-2">
                <button
                    className="border px-2 cursor-pointer"
                    onClick={() => addToCart(product)}
                    data-testid='cart-item-amount-increase'
                >+</button>
                <span data-testid='cart-item-amount font-raleway'>{product.quantity}</span>
                <button
                    className="border px-2 cursor-pointer"
                    onClick={() => removeFromCart(product)}
                    data-testid='cart-item-amount-decrease'
                >−</button>
            </div>
            {/* IMAGE */}
            <div className="flex flex-col justify-center">
                <img src={product.gallery[0]} alt={product.name} className="w-19 h-auto" />
            </div>
        </div>
    );
};
