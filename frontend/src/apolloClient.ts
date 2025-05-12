import { ApolloClient, InMemoryCache } from '@apollo/client';

const client = new ApolloClient({
    uri: 'https://davit-ecommerce.store/api/',
    cache: new InMemoryCache(),
});

export default client;
