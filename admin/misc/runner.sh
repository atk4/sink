#!/bin/bash

echo "BANG" >&2

for x in 1 2 3 4 5 6 7 8 9 10; do
    echo -n "Iteration $x of 10: "
    uptime
    sleep 1
done

echo "Completed"

