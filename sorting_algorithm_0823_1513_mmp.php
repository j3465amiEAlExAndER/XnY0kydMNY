<?php
// 代码生成时间: 2025-08-23 15:13:20
class SortingAlgorithm {

    /**
     * Sorts an array using bubble sort algorithm.
     *
     * @param array $array The array to sort.
     * @return array The sorted array.
     */
    public function bubbleSort(array $array): array {
        // Ensure the input is an array
        if (!is_array($array)) {
            throw new InvalidArgumentException('Input must be an array.');
        }

        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $length - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // Swap elements
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }

        return $array;
    }

    /**
     * Sorts an array using insertion sort algorithm.
     *
     * @param array $array The array to sort.
     * @return array The sorted array.
     */
    public function insertionSort(array $array): array {
        // Ensure the input is an array
        if (!is_array($array)) {
            throw new InvalidArgumentException('Input must be an array.');
        }

        for ($i = 1; $i < count($array); $i++) {
            $key = $array[$i];
            $j = $i - 1;

            // Move elements of array[0..i-1], that are greater than key, to one position ahead of their current position
            while ($j >= 0 && $array[$j] > $key) {
                $array[$j + 1] = $array[$j];
                $j = $j - 1;
            }
            $array[$j + 1] = $key;
        }

        return $array;
    }

    // Additional sorting algorithms can be added here
    // ...
}

// Example usage
try {
    $sorter = new SortingAlgorithm();
    $arrayToSort = [34, 7, 23, 32, 5, 62];
    $bubbleSortedArray = $sorter->bubbleSort($arrayToSort);
    $insertionSortedArray = $sorter->insertionSort($arrayToSort);

    echo "Bubble Sorted Array: " . implode(', ', $bubbleSortedArray) . "
";
    echo "Insertion Sorted Array: " . implode(', ', $insertionSortedArray) . "
";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
