<?php
// 代码生成时间: 2025-08-10 17:11:59
class SortAlgorithm {

    /**
     * Sorts an array using bubble sort algorithm.
     *
     * @param array $array The array to be sorted.
     * @return array The sorted array.
     */
    public function bubbleSort(array $array): array {
        if (empty($array)) {
            throw new InvalidArgumentException('The array is empty and cannot be sorted.');
        }

        $n = count($array);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
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
     * @param array $array The array to be sorted.
     * @return array The sorted array.
     */
    public function insertionSort(array $array): array {
        if (empty($array)) {
            throw new InvalidArgumentException('The array is empty and cannot be sorted.');
        }

        for ($i = 1; $i < count($array); $i++) {
            $key = $array[$i];
            $j = $i - 1;

            while ($j >= 0 && $array[$j] > $key) {
                $array[$j + 1] = $array[$j];
                $j--;
            }
            $array[$j + 1] = $key;
        }

        return $array;
    }

    // Additional sorting algorithms can be added here...

}

// Example usage
try {
    $sorter = new SortAlgorithm();
    $arrayToSort = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5];
    $sortedArray = $sorter->bubbleSort($arrayToSort);
    echo 'Sorted array by bubble sort: ' . implode(', ', $sortedArray) . "
";

    $sortedArray = $sorter->insertionSort($arrayToSort);
    echo 'Sorted array by insertion sort: ' . implode(', ', $sortedArray) . "
";
} catch (InvalidArgumentException $e) {
    echo 'Error: ' . $e->getMessage();
}
