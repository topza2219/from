import React, { useState } from 'react';
import { View, Text, Button, StyleSheet } from 'react-native';
import { RNCamera } from 'react-native-camera';
import axios from 'axios';

const NFCScanScreen = () => {
    const [scannedData, setScannedData] = useState(null);

    const handleBarCodeRead = async ({ data }) => {
        setScannedData(data);
        // Call the API to process the scanned NFC data
        try {
            const response = await axios.post('http://your-api-url/scan_nfc', { nfcData: data });
            console.log('NFC Data processed:', response.data);
        } catch (error) {
            console.error('Error processing NFC data:', error);
        }
    };

    return (
        <View style={styles.container}>
            <RNCamera
                style={styles.preview}
                onBarCodeRead={handleBarCodeRead}
                captureAudio={false}
            />
            {scannedData && (
                <View style={styles.resultContainer}>
                    <Text style={styles.resultText}>Scanned Data: {scannedData}</Text>
                    <Button title="Clear" onPress={() => setScannedData(null)} />
                </View>
            )}
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    preview: {
        width: '100%',
        height: '100%',
    },
    resultContainer: {
        position: 'absolute',
        bottom: 50,
        left: 20,
        right: 20,
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 10,
        alignItems: 'center',
    },
    resultText: {
        fontSize: 16,
        marginBottom: 10,
    },
});

export default NFCScanScreen;