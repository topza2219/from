import React, { useEffect, useState } from 'react';
import { View, Text, Button, ActivityIndicator, StyleSheet } from 'react-native';
import axios from 'axios';

const RiskAnalysisScreen = () => {
    const [riskData, setRiskData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchRiskData = async () => {
            try {
                const response = await axios.get('API_ENDPOINT_FOR_RISK_ANALYSIS');
                setRiskData(response.data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchRiskData();
    }, []);

    if (loading) {
        return <ActivityIndicator size="large" color="#0000ff" />;
    }

    if (error) {
        return (
            <View style={styles.container}>
                <Text>Error: {error}</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Risk Analysis Results</Text>
            {/* Render riskData here */}
            {riskData && riskData.map((item, index) => (
                <View key={index} style={styles.item}>
                    <Text>{item.description}</Text>
                    <Text>Risk Level: {item.riskLevel}</Text>
                </View>
            ))}
            <Button title="Refresh" onPress={() => setLoading(true)} />
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        justifyContent: 'center',
        alignItems: 'center',
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 20,
    },
    item: {
        marginVertical: 10,
        padding: 10,
        borderWidth: 1,
        borderColor: '#ccc',
        borderRadius: 5,
        width: '100%',
    },
});

export default RiskAnalysisScreen;