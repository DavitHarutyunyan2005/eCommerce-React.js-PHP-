import { ApolloClient, InMemoryCache } from '@apollo/client';

const client = new ApolloClient({
    uri: 'http://localhost/Dashboard/new/backend/public/',
    cache: new InMemoryCache(),
});

export default client;
