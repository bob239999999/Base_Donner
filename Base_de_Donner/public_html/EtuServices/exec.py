import redis
import sys
import time

def main(email, password):
    try:
        # Connect to Redis
        r = redis.Redis(host='localhost', port=6379, db=0)

        # Verify the connection
        if not r.ping():
            return "Failed to connect to Redis!"

        # Check if the user is blocked
        block_key = f"block:{email}"
        blocked = r.get(block_key)
        if blocked:
            return "blocked"

        # Check if the user has exceeded the maximum number of accesses in the last 10 minutes
        current_time = int(time.time())
        window_start = current_time - 600  # 10 minute window
        user_key = f"user:{email}"
        user_connections = r.zcount(user_key, window_start, current_time)

        if user_connections >= 3:
            # Calculate the waiting time (in seconds) until the next change
            wait_time = 600 - (current_time - r.zrange(user_key, -1, -1, withscores=True)[0][1])
            return str(wait_time)

        # If the user has not exceeded the limit, increment the access count
        r.zadd(user_key, {current_time: current_time})

        # Return 0 if the user is not blocked and does not need to wait
        return "0"
    except redis.ConnectionError as e:
        return f"Error connecting to Redis: {e}"

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python exec.py <email> <password>")
    else:
        email = sys.argv[1]
        password = sys.argv[2]
        wait_time = main(email, password)
        print(wait_time)
