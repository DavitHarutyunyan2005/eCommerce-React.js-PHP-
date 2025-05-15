import { Routes, Route, Navigate } from 'react-router-dom';
import { FC } from 'react';
import ProductListingPage from './pages/ProductListingPage.tsx';
import ProductDetailsPage from './pages/ProductDetailsPage.tsx';
import Header from './pages/Header.tsx';

const App: FC = () => {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<Navigate to="/clothes" replace />} />

        <Route path="/all" element={<ProductListingPage category="all" madeFor='all'/>} />

        <Route path="/tech">
          <Route index element={<ProductListingPage category="tech" madeFor='all'/>} />
          <Route path="product/:productId" element={<ProductDetailsPage />} />
        </Route>

        <Route path="/clothes">
          <Route index element={<Navigate to="/clothes/women" replace />} />
          <Route path="women" element={<ProductListingPage category="clothes" madeFor="women" />} />
          <Route path="men" element={<ProductListingPage category="clothes" madeFor="men" />} />
          <Route path="kids" element={<ProductListingPage category="clothes" madeFor="kids" />} />

          <Route path="women/product/:productId" element={<ProductDetailsPage />} />
          <Route path="men/product/:productId" element={<ProductDetailsPage />} />
          <Route path="kids/product/:productId" element={<ProductDetailsPage />} />
        </Route>
      </Routes>
    </>
  );
};

export default App;
