import React, { useEffect, useState } from 'react';
import { View, Text, Button, FlatList, Alert } from 'react-native';
import { getPendingReturns, confirmReturn } from '../api/return_item';

const ReturnScreen = () => {
    const [pendingReturns, setPendingReturns] = useState([]);

    useEffect(() => {
        fetchPendingReturns();
    }, []);

    const fetchPendingReturns = async () => {
        try {
            const response = await getPendingReturns();
            setPendingReturns(response.data);
        } catch (error) {
            Alert.alert('Error', 'Failed to fetch pending returns');
        }
    };

    const handleConfirmReturn = async (returnId) => {
        try {
            await confirmReturn(returnId);
            Alert.alert('Success', 'Return confirmed successfully');
            fetchPendingReturns(); // Refresh the list
        } catch (error) {
            Alert.alert('Error', 'Failed to confirm return');
        }
    };

    const renderReturnItem = ({ item }) => (
        <View>
            <Text>{item.description}</Text>
            <Button title="Confirm Return" onPress={() => handleConfirmReturn(item.id)} />
        </View>
    );

    return (
        <View>
            <Text>Pending Returns</Text>
            <FlatList
                data={pendingReturns}
                renderItem={renderReturnItem}
                keyExtractor={(item) => item.id.toString()}
            />
        </View>
    );
};

export default ReturnScreen;