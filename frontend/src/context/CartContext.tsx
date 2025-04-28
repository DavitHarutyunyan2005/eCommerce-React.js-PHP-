import React, { createContext, useContext, useState, useEffect, ReactNode, useRef } from 'react';
import { Product } from '../types/ProductType';
import { OrderProduct } from '../types/OrderProductType';


interface CartContextType {
    cart: OrderProduct[];
    totalQuantity: number;
    totalPrice: number;
    addToCart: (product: Product) => void;
    itemInCart: (product: Product) => number;
    itemInCartWithoutAttributes: (product: Product) => number;
    updateItemFromCart: (product: OrderProduct) => void;
    removeFromCart: (product: any) => void;
    clearCart: () => void;
    isCartOpen: boolean;
    setIsCartOpen: (isOpen: boolean) => void;
    findProuctById: (id: string) => OrderProduct | undefined;
}

interface CartProviderProps {
    children: ReactNode;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) throw new Error('useCart must be used within a CartProvider');
    return context;
};

export const CartProvider: React.FC<CartProviderProps> = ({ children }) => {
    const [cart, setCart] = useState<OrderProduct[]>(() => {
        const savedCart = localStorage.getItem('cart');
        return savedCart ? JSON.parse(savedCart) : [];
    });

    const [isCartOpen, setIsCartOpen] = useState(false);

    const isFirstRender = useRef(true);

    useEffect(() => {
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }
        localStorage.setItem('cart', JSON.stringify(cart));
    }, [cart]);

    const isSameAttributes = (a: any[], b: any[]) =>
        a.length === b.length &&
        a.every((attr, i) => JSON.stringify(attr) === JSON.stringify(b[i]));

    const addToCart = (incomingProduct: Product | OrderProduct) => {

    // Mark the first item in each attribute as selected if none are selected (for 'Quick Shop' button)
    const updatedAttributes = incomingProduct.attributes.map(attr => {
            if (attr.items.every(item => !item.selected)) {
                return {
                    ...attr,
                    items: attr.items.map((item, index) => ({
                        ...item,
                        selected: index === 0,
                    })),
                };
            }
            return attr;
        });
    
        const product: Product | OrderProduct = {
            ...incomingProduct,
            attributes: updatedAttributes,
        };
    
        setCart(prevCart => {
            const existingProduct = prevCart.find(
                item => item.id === product.id && isSameAttributes(item.attributes, product.attributes)
            );
    
            if (existingProduct) {
                return prevCart.map(item =>
                    item.id === existingProduct.id && isSameAttributes(item.attributes, existingProduct.attributes)
                        ? { ...item, quantity: item.quantity + 1 }
                        : item
                );
            }
    
            let orderItemId: number;
            do {
                orderItemId = Math.floor(10000000 + Math.random() * 90000000);
            } while (prevCart.some(item => item.orderItemId === orderItemId));
    
            return [...prevCart, { ...product, quantity: 1, orderItemId } as OrderProduct];
        });
    
        setIsCartOpen(true);
    };
    
    const removeFromCart = (product: any) => {
        setCart(prevCart => {
            return prevCart
                .map(item => {
                    const isMatch = product.orderItemId
                        ? item.orderItemId === product.orderItemId
                        : item.id === product.id &&
                        isSameAttributes(item.attributes, product.attributes);

                    if (isMatch) {
                        if (item.quantity > 1) {
                            return { ...item, quantity: item.quantity - 1 };
                        } else {
                            return null;
                        }
                    }
                    return item;
                })
                .filter(item => item !== null);
        });
    };

    const updateItemFromCart = (product: OrderProduct) => {
        setCart(prevCart => {
            return prevCart.map(item =>
                item.orderItemId === product.orderItemId
                    ? { ...product }
                    : item
            );
        });
        console.log('updateItemFromCart', product);
    }

    const clearCart = () => {
        setCart([]);
    };

    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => {
        const price = item.prices.find((price) => price.currency.label === 'USD')?.amount || 0;
        return sum + price * item.quantity;
    }, 0);

    // This is used to have the quantity of the product in the cart (for 'Add to Cart' button)
    const itemInCart = (product: Product): number => {
        return cart
            .filter(item => item.id === product.id && isSameAttributes(item.attributes, product.attributes))
            .reduce((total, item) => total + (item.quantity || 1), 0);
    };

    // This is used to have the quantity of the product in the cart inspite of the specific attributes. Used in ProductCart (Quick Shop)
    const itemInCartWithoutAttributes = (product: Product | OrderProduct): number => {
        return cart
            .filter(item => item.id === product.id)
            .reduce((total, item) => total + (item.quantity || 1), 0);
    };

    const findProuctById = (id: string): OrderProduct | undefined => {
        return cart.find(item => item.id === id); 
    }

    return (
        <CartContext.Provider
            value={{
                cart, addToCart, removeFromCart, clearCart, totalQuantity,
                totalPrice, isCartOpen, setIsCartOpen, itemInCart,
                updateItemFromCart, findProuctById, itemInCartWithoutAttributes
            }}>
            {children}
        </CartContext.Provider>
    );
};
