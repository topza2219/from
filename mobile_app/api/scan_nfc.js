const NFCReader = require('nfc-pcsc'); // Import NFC library

const nfc = new NFCReader(); // Create NFC reader instance

// Function to scan NFC
const scanNFC = () => {
    nfc.on('reader', async reader => {
        console.log(`NFC reader detected: ${reader.reader.name}`);

        reader.on('card', async card => {
            console.log(`Card detected: ${JSON.stringify(card)}`);

            // Here you can handle the card data, e.g., send it to the server
            try {
                const response = await fetch('https://your-api-endpoint.com/api/borrow_item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cardData: card.uid }),
                });

                const data = await response.json();
                console.log('Response from server:', data);
            } catch (error) {
                console.error('Error communicating with server:', error);
            }
        });

        reader.on('error', err => {
            console.error('Error reading card:', err);
        });

        reader.on('end', () => {
            console.log(`NFC reader ${reader.reader.name} disconnected`);
        });
    });

    nfc.on('error', err => {
        console.error('NFC reader error:', err);
    });
};

// Start scanning for NFC cards
scanNFC();