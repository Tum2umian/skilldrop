def estimate_cost(fp, hours_per_fp, hourly_rate):
    effort = fp * hours_per_fp
    total_cost = effort * hourly_rate
    return total_cost

# Prompt user for inputs
fp = int(input("Enter the number of Function Points (FP): "))
hours_per_fp = float(input("Enter the effort per FP (hours): "))
hourly_rate = float(input("Enter the developer hourly rate ($): "))

# Calculate cost
cost = estimate_cost(fp, hours_per_fp, hourly_rate)
ugxCost = cost * 3600

# Display result
print("\n===== Cost Estimation Results =====")
print(f"Function Points: {fp}")
print(f"Effort per FP: {hours_per_fp} hours")
print(f"Hourly Rate: ${hourly_rate}")
print(f"Total Estimated Cost: ${cost:.2f}")
print(f"Total Estimated Cost: UGX {ugxCost:.2f}")