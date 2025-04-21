import React from 'react';
import { Product } from '../../types/ProductType';
import { FaShoppingCart } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';
import clsx from 'clsx';

interface ProductCardProps {
    product: Product;
}



const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
    const navigate = useNavigate();

    const goToDetails = () => {
        navigate(`/product/${product.id}`, {
            state: { product },
        });
    };

    const price = product.prices[0];

    return (
        <div className="relative w-64 bg-white rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-lg group cursor-pointer"
            onClick={goToDetails}>
            <div className="relative">
                <img
                    src={product.gallery[0]}
                    alt={product.name}
                    className={clsx(
                        'w-full h-48 object-cover',
                        !product.inStock && 'filter grayscale opacity-50'
                    )}
                />

                {/* OUT OF STOCK overlay */}
                {!product.inStock && (
                    <div className="absolute inset-0 flex items-center justify-center">
                        <span className="text-gray-500 text-2xl font-semibold transform -rotate-12">
                            OUT OF STOCK
                        </span>
                    </div>
                )}

                {/* Cart icon on image border bottom-right */}
                {product.inStock && (<div className="absolute bottom-0 right-8 translate-x-1/2 translate-y-1/2 group-hover:opacity-100 opacity-0 transition-opacity duration-300 ">
                    <button className="bg-green-500 text-white p-2 rounded-full shadow-md cursor-pointer">
                        <FaShoppingCart size={20} />
                    </button>
                </div>)}
            </div>

            <div className="p-4">
                <h3 className="text-lg font-medium text-gray-800">{product.name}</h3>
                <p className="text-gray-600">
                    {price.currency.symbol}
                    {price.amount.toFixed(2)}
                </p>
            </div>
        </div>
    );
};

export default ProductCard;
