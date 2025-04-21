import React, { useState } from 'react';
import { useLocation, useParams } from 'react-router-dom';
import { useQuery } from '@apollo/client';
import { Product as ProductType } from '../../types/ProductType';
import { GET_PRODUCT_BY_ID } from '../../graphql/queries/GET_PRODUCTS';
import parse from 'html-react-parser';
import './normalize.css';


const ProductDetailsPage: React.FC = () => {
    const { state } = useLocation();
    const { productId } = useParams<{ productId: string }>();

    const productFromState = state?.product as ProductType;

    const { data, loading, error } = useQuery(GET_PRODUCT_BY_ID, {
        variables: { id: productId },
        skip: !!productFromState,
    });

    const productFromQuery = data?.product as ProductType;

    const product = productFromState || productFromQuery;

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;
    if (!product) return <p>No product found.</p>;

    const sizes = product.attributes.find((attr: any) => attr.name === 'Size')?.items.map((i: any) => i.value) || [];
    const colors = product.attributes.find((attr: any) => attr.name === 'Color')?.items.map((i: any) => i.value) || [];

    const [selectedSize, setSelectedSize] = useState<string>(sizes[0] || '');
    const [selectedColor, setSelectedColor] = useState<string>(colors[0] || '');
    const [currentImageIndex, setCurrentImageIndex] = useState<number>(0);

    const price = product.prices[0];

    return (
        <div className="flex flex-col md:flex-row gap-6 p-6 max-w-4xl mx-auto">
            {/* Left: Image Carousel */}
            <div className="flex flex-col gap-4">
                <div className="flex flex-col gap-2">
                    {product.gallery.map((image: any, index: number) => (
                        <img
                            key={index}
                            src={image}
                            alt={`Thumbnail ${index + 1}`}
                            className={`w-16 h-16 object-cover cursor-pointer border-2 ${currentImageIndex === index ? 'border-green-500' : 'border-transparent'}`}
                            onClick={() => setCurrentImageIndex(index)}
                        />
                    ))}
                </div>
                <div className="relative">
                    <img
                        src={product.gallery[currentImageIndex]}
                        alt="Product"
                        className="w-full h-96 object-cover"
                    />
                    <button
                        onClick={() => setCurrentImageIndex(prev => (prev === 0 ? product.gallery.length - 1 : prev - 1))}
                        className="absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full"
                    >
                        &larr;
                    </button>
                    <button
                        onClick={() => setCurrentImageIndex(prev => (prev === product.gallery.length - 1 ? 0 : prev + 1))}
                        className="absolute top-1/2 right-2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full"
                    >
                        &rarr;
                    </button>
                </div>
            </div>

            {/* Right: Product Info */}
            <div className="flex flex-col gap-4">
                <h2 className="text-2xl font-bold">{product.name}</h2>

                {sizes.length > 0 && (
                    <div>
                        <p className="text-sm font-semibold">SIZE:</p>
                        <div className="flex gap-2 mt-2">
                            {sizes.map((size: any) => (
                                <button
                                    key={size}
                                    onClick={() => setSelectedSize(size)}
                                    className={`border-2 px-4 py-2 ${selectedSize === size ? 'bg-black text-white' : 'bg-white text-black'}`}
                                >
                                    {size}
                                </button>
                            ))}
                        </div>
                    </div>
                )}

                {colors.length > 0 && (
                    <div>
                        <p className="text-sm font-semibold">COLOR:</p>
                        <div className="flex gap-2 mt-2">
                            {colors.map((color: any) => (
                                <button
                                    key={color}
                                    onClick={() => setSelectedColor(color)}
                                    className={`w-8 h-8 rounded-full border-2 ${selectedColor === color ? 'border-green-500' : 'border-transparent'}`}
                                    style={{ backgroundColor: color }}
                                />
                            ))}
                        </div>
                    </div>
                )}

                <p className="text-lg font-semibold">
                    PRICE: {price.currency.symbol}{price.amount.toFixed(2)}
                </p>


                <div className='relative'>
                    <button
                        disabled={!product.inStock}
                        className={
                            'absolute py-3 px-6 font-semibold transition-colors duration-200 ' +
                            'text-white w-full bg-[#5ECE7B] hover:bg-green-600 cursor-pointer'
                        }
                    >
                        ADD TO CART
                    </button>
                    {!product.inStock && <div className='absolute w-full bg-[#40424A] opacity-45 left-0 top-0 h-12 cursor-not-allowed'></div>}
                </div>

                <div id='parse' className="text-gray-600 mt-10">{parse(product.description)}</div>
            </div>

        </div>
        
    );
};

export default ProductDetailsPage;
