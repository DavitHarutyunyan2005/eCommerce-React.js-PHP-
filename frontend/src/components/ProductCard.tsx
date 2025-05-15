import React from 'react';
import { Product } from '../types/ProductType';
import { FaShoppingCart } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';
import clsx from 'clsx';
import { useCart } from '../context/CartContext';
import { OrderProduct } from '../types/OrderProductType';
import { toKebabCase, truncateString } from '../utils/stringUtils';

interface ProductCardProps {
    product: Product | OrderProduct;
}

const ProductCard: React.FC<ProductCardProps> = ({ product }) => {

    const { addToCart, itemInCartWithoutAttributes } = useCart();

    const navigate = useNavigate();

    const goToDetails = () => {
        const category = product.category.toLowerCase();
        const madeForSegment = product.madeFor !== 'all' ? `/${product.madeFor.toLowerCase()}` : '';
        const path = `/${category}${madeForSegment}/product/${product.id}`;

        navigate(path, { state: { product } });
    };


    const handleAddToCart = (e: React.MouseEvent, product: Product | OrderProduct) => {
        e.stopPropagation();
        addToCart(product);
    }

    const price = product.prices[0];

    console.log(`product-${toKebabCase(product.name)}`);

    return (
        <div
            className={
                "relative w-64 bg-white rounded-lg overflow-hidden " +
                "transition-shadow duration-300 hover:shadow-lg group cursor-pointer"
            }
            onClick={goToDetails}
            data-testid={`product-${toKebabCase(product.name)}`}
        >
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
                {product.inStock && (
                    <div className="absolute bottom-0 right-8 translate-x-1/2 translate-y-1/2 group-hover:opacity-100 opacity-0 transition-opacity duration-300 ">
                        <button onClick={(e) => handleAddToCart(e, product)} className="bg-green-500 text-white p-2 rounded-full shadow-md cursor-pointer">
                            <FaShoppingCart size={20} />
                        </button>
                        {/* badge of the quantity of the product already in cart */}
                        {itemInCartWithoutAttributes(product) > 0 && (
                            <span className="absolute -top-1 -right-1.5 bg-black text-white text-xs font-bold rounded-full w-4.5 h-4.5 flex items-center justify-center text-center">
                                {itemInCartWithoutAttributes(product)}
                            </span>
                        )}
                    </div>)}
            </div>

            <div className="p-4 font-raleway">
                <h3 className="text-lg font-medium text-gray-800">{truncateString(product.name, 28)}</h3>
                <p className="text-gray-600">
                    {price.currency.symbol}
                    {price.amount.toFixed(2)}
                </p>
            </div>
        </div>
    );
};

export default ProductCard;
