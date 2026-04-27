import requests

LOCAL_FILE = "./3"
REMOTE_URL = "http://torium.fun/asset/?id=3"

# Read local file
with open(LOCAL_FILE, "rb") as f:
    local_data = f.read()

# Fetch remote file
response = requests.get(REMOTE_URL)
if response.status_code != 200:
    print(f"Failed to fetch remote file. Status code: {response.status_code}")
    exit(1)

remote_data = response.content

# Compare
if local_data == remote_data:
    print("✅ Files are identical!")
else:
    print("❌ Files differ!")

    # Optional: show difference in length
    print(f"Local file size:  {len(local_data)} bytes")
    print(f"Remote file size: {len(remote_data)} bytes")

    # Optional: find first byte that differs
    for i, (b1, b2) in enumerate(zip(local_data, remote_data)):
        if b1 != b2:
            print(f"First difference at byte {i}: local={b1}, remote={b2}")
            break
