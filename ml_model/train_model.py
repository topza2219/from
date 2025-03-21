import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score, confusion_matrix
import joblib

# Load dataset
data = pd.read_csv('data/borrow_data.csv')  # Update with the actual path to your dataset

# Preprocess data
X = data.drop('target', axis=1)  # Features
y = data['target']  # Target variable

# Split the dataset into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Initialize the model
model = RandomForestClassifier()

# Train the model
model.fit(X_train, y_train)

# Make predictions
predictions = model.predict(X_test)

# Evaluate the model
accuracy = accuracy_score(y_test, predictions)
precision = precision_score(y_test, predictions, average='weighted')
recall = recall_score(y_test, predictions, average='weighted')
f1 = f1_score(y_test, predictions, average='weighted')
conf_matrix = confusion_matrix(y_test, predictions)

print(f'Model Accuracy: {accuracy * 100:.2f}%')
print(f'Model Precision: {precision * 100:.2f}%')
print(f'Model Recall: {recall * 100:.2f}%')
print(f'Model F1 Score: {f1 * 100:.2f}%')
print('Confusion Matrix:')
print(conf_matrix)

# Save the model
joblib.dump(model, 'model.pkl')  # Save the trained model to a file