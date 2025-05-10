import React from 'react';

const ProductCardSkeleton: React.FC = () => {
    return (
        <div className="w-64 bg-white rounded-lg overflow-hidden animate-pulse">
            <div className="w-full h-48 bg-gray-300" />
            <div className="p-4">
                <div className="h-4 bg-gray-300 rounded w-3/4 mb-2" />
                <div className="h-4 bg-gray-300 rounded w-1/2" />
            </div>
        </div>
    );
};

export default ProductCardSkeleton;
