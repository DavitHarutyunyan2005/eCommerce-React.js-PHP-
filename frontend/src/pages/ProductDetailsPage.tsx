import React, { useEffect, useState } from 'react';
import { useLocation, useParams } from 'react-router-dom';
import { Product as ProductType } from '../types/ProductType';
import parse from 'html-react-parser';
import clsx from 'clsx';
import './ProductDetails.css';
import { FaChevronLeft, FaChevronRight, FaMinus, FaPlus } from 'react-icons/fa';
import { useCart } from '../context/CartContext';
import { CartOverlay } from '../components/CartOverlay/CartOverlay';
import { OrderProduct } from '../types/OrderProductType';
import { toKebabCase } from '../utils/stringUtils';
import { sortSizes } from '../utils/sortSizes';


const ProductDetailsPage: React.FC = () => {
    const { state } = useLocation();
    const { productId } = useParams<{ productId: string }>();
    const { isCartOpen, setIsCartOpen, itemInCart, addToCart, removeFromCart, findProuctById } = useCart();
    const productFromState = state?.product as ProductType;
    const productFromCart = findProuctById(productId as string);
    const [product, setProduct] = useState<ProductType | OrderProduct>(productFromCart || productFromState);
    const [currentImageIndex, setCurrentImageIndex] = useState<number>(0);
    const price = product.prices[0];

    // Adding an event listener for keydown events to navigate through images
    useEffect(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'ArrowLeft') {
                setCurrentImageIndex(prev => (prev === 0 ? product.gallery.length - 1 : prev - 1));
            } else if (event.key === 'ArrowRight') {
                setCurrentImageIndex(prev => (prev === product.gallery.length - 1 ? 0 : prev + 1));
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [product.gallery.length]);


    const handleAttributeItemChange = (attrName: string, itemValue: string) => {
        setProduct((prevProduct) => {
            const updatedAttributes = prevProduct.attributes.map((attr: any) => {
                if (attr.name === attrName) {
                    return {
                        ...attr,
                        items: attr.items.map((item: any) => ({
                            ...item,
                            selected: item.value === itemValue,
                        })),
                    };
                }
                return attr;
            });
            return { ...prevProduct, attributes: updatedAttributes };
        });
    }

    const areAttributesSelected = product.attributes.every((attr: any) => {
        return attr.items.some((item: any) => item.selected);
    });

    return (
        <div className="flex flex-col justify-center md:flex-row gap-12  p-6">
            {isCartOpen && (
                <div
                    className="fixed inset-0 bg-[#40424A] opacity-45 z-2"
                    onClick={() => setIsCartOpen(false)}
                />
            )}

            {/* Image Carousel */}
            <div
                className="flex gap-4"
                data-testid='product-gallery'
            >
                {/* Secondary pictures */}
                {product.gallery.length > 1 &&
                    <div className="flex flex-col gap-2 max-h-[516px] overflow-auto scrollable">
                        {product.gallery.map((image: any, index: number) => (
                            <img
                                key={index}
                                src={image}
                                alt={`Thumbnail ${index + 1}`}
                                className={`w-16 h-16 object-cover cursor-pointer hover:opacity-60 border-2  ${currentImageIndex === index ? 'border-green-500' : 'border-transparent'}`}
                                onClick={() => setCurrentImageIndex(index)}
                            />
                        ))}
                    </div>
                }
                {/* Main picture */}
                <div className="relative h-fit">
                    <img
                        src={product.gallery[currentImageIndex]}
                        alt="Product"
                        className="w-144 h-129 object-cover"
                    />
                    {product.gallery.length > 1 &&
                        <>
                            <button
                                onClick={() => setCurrentImageIndex(prev => (prev === 0 ? product.gallery.length - 1 : prev - 1))}
                                className="absolute top-1/2 left-2 bg-gray-800 text-white p-2 cursor-pointer"
                            >
                                <FaChevronLeft />
                            </button>
                            <button
                                onClick={() => setCurrentImageIndex(prev => (prev === product.gallery.length - 1 ? 0 : prev + 1))}
                                className="absolute top-1/2 right-2  bg-gray-800 text-white p-2 cursor-pointer"
                            >
                                <FaChevronRight />
                            </button>
                        </>
                    }
                </div>
            </div>

            {/* Product Info */}
            <div className="flex flex-col gap-6 md:w-1/5 justify-center">
                <h2 className="text-3xl font-bold font-raleway">{product.name}</h2>

                {/* Attribute Options */}
                {product.attributes.map((attr: any) => {
                    if (attr.type === 'text') {
                        return (
                            <div
                                key={attr.id}
                                className="flex flex-col gap-2"
                                data-testid={`product-attribute-${toKebabCase(attr.name)}`}
                            >
                                <p className="text-lg font-semibold">{attr.name.toUpperCase()}:</p>
                                <div className="flex gap-2 mt-2">
                                    {attr.items
                                        .slice() 
                                        .sort((a: any, b: any) => sortSizes([a.value, b.value])[0] === a.value ? -1 : 1)
                                        .map((item: any) => (
                                            <button
                                                key={item.id}
                                                onClick={() => handleAttributeItemChange(attr.name, item.value)}
                                                className={clsx(
                                                    'border-2 px-4 py-2 cursor-pointer',
                                                    item.selected ? 'bg-black text-white' : 'bg-white text-black'
                                                )}
                                            >
                                                {item.value}
                                            </button>
                                        ))}

                                </div>
                            </div>
                        );
                    }
                    if (attr.type === 'swatch') {
                        return (
                            <div
                                key={attr.id}
                                className="flex flex-col gap-2"
                                data-testid={`product-attribute-${toKebabCase(attr.name)}`}
                            >
                                <p className="text-lg font-semibold">{attr.name.toUpperCase()}:</p>
                                <div className="flex gap-2 mt-2">
                                    {attr.items.map((item: any) => (
                                        <button
                                            key={item.value}
                                            onClick={() => handleAttributeItemChange(attr.name, item.value)}
                                            className={`w-9 h-9 cursor-pointer ${item.selected ? 'border-green-500 border-3' : 'border-1 border-black'}`}
                                            style={{ backgroundColor: item.value }}
                                        />
                                    ))}
                                </div>
                            </div>
                        );
                    }
                    return null;
                })}

                <p className="text-lg font-semibold flex flex-col gap-3">
                    <span>PRICE:</span>
                    <span className='font-raleway'>{price.currency.symbol}{price.amount.toFixed(2)}</span>
                </p>

                {/* Add to Cart button */}
                {itemInCart(product) === 0 ?
                    <div className="relative" >
                        <button
                            data-testid='add-to-cart'
                            disabled={!product.inStock}
                            onClick={() => addToCart(product)}
                            className={
                                'py-3 px-6 font-semibold transition-colors duration-400 ' +
                                'text-white w-full bg-[#5ECE7B] hover:bg-green-600 cursor-pointer'
                            }
                        >
                            ADD TO CART
                        </button>
                        {(!product.inStock || !areAttributesSelected) && (
                            <div className="absolute top-0 left-0 w-full h-full bg-[#40424A] opacity-45 cursor-not-allowed"></div>
                        )}
                    </div> : (
                        <div
                            className={
                                'py-2 px-3 font-semibold transition-colors duration-400 ' +
                                'text-white w-full bg-[#5ECE7B] ' +
                                'flex justify-between min-w-70'}
                        >
                            <button
                                className='border-3 border-white rounded-full p-0.5 cursor-pointer hover:opacity-50'
                                onClick={() => removeFromCart(product)}

                            >
                                <FaMinus size={21} color='white' />
                            </button>
                            <span className='text-white font-raleway'>{itemInCart(product)} ADDED TO CART</span>
                            <button
                                data-testid='add-to-cart'
                                className='border-3 border-white rounded-full p-0.5 cursor-pointer hover:opacity-50'
                                onClick={() => addToCart(product)}
                            >
                                <FaPlus size={21} color='white' />
                            </button>
                        </div>
                    )
                }

                <div
                    id='parse'
                    className="text-gray-600 mt-10"
                    data-testid='product-description'
                >
                    {parse(product.description)}
                </div>
            </div>

            {isCartOpen && (
                <div className="fixed top-18 right-15 z-10 overflow-y-auto max-h-150">
                    <CartOverlay />
                </div>
            )}
        </div>
    );
};

export default ProductDetailsPage;