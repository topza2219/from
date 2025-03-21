import pandas as pd
import joblib
import sys

def load_model(model_path):
    """Load the trained model from a file."""
    model = joblib.load(model_path)
    return model

def predict_risk(data, model):
    """Predict risk using the loaded model."""
    prediction = model.predict(data)
    return prediction

def main(input_file, output_file):
    # Load the trained model
    model_path = 'model.pkl'
    model = load_model(model_path)

    # Load input data from CSV file
    input_data = pd.read_csv(input_file)

    # Predict risk
    risk_prediction = predict_risk(input_data, model)

    # Save predictions to a new CSV file
    output_data = input_data.copy()
    output_data['risk_prediction'] = risk_prediction
    output_data.to_csv(output_file, index=False)
    print(f'Predictions saved to {output_file}')

if __name__ == '__main__':
    if len(sys.argv) != 3:
        print("Usage: python predict_risk.py <input_file> <output_file>")
    else:
        input_file = sys.argv[1]
        output_file = sys.argv[2]
        main(input_file, output_file)