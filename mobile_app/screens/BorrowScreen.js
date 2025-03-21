import React, { useState, useEffect } from 'react';
import { View, Text, Button, FlatList, Alert } from 'react-native';
import { borrowItemAPI } from '../api/borrow_item';

const BorrowScreen = () => {
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchItems();
    }, []);

    const fetchItems = async () => {
        try {
            const response = await borrowItemAPI.getAvailableItems();
            setItems(response.data);
        } catch (error) {
            Alert.alert('Error', 'Failed to fetch items');
        } finally {
            setLoading(false);
        }
    };

    const handleBorrowItem = async (itemId) => {
        try {
            await borrowItemAPI.borrowItem(itemId);
            Alert.alert('Success', 'Item borrowed successfully');
            fetchItems(); // Refresh the item list
        } catch (error) {
            Alert.alert('Error', 'Failed to borrow item');
        }
    };

    if (loading) {
        return (
            <View>
                <Text>Loading...</Text>
            </View>
        );
    }

    return (
        <View>
            <Text>Available Items</Text>
            <FlatList
                data={items}
                keyExtractor={(item) => item.id.toString()}
                renderItem={({ item }) => (
                    <View>
                        <Text>{item.name}</Text>
                        <Button title="Borrow" onPress={() => handleBorrowItem(item.id)} />
                    </View>
                )}
            />
        </View>
    );
};

export default BorrowScreen;