const API_URL = 'http://your-api-url.com/api/predict_risk'; // Replace with your actual API URL

async function predictRisk(data) {
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error predicting risk:', error);
        throw error;
    }
}

// Example usage
// const riskData = { /* your risk data here */ };
// predictRisk(riskData).then(result => console.log(result));