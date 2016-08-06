<?

namespace rude;

/**
 * @category math
 */
class math
{
	/**
	 * @en Factorial calculator
	 * @ru Вычисление факториала числа
	 *
	 * $result = math::factorial(3); # int(6)
	 *
	 * @param int $number
	 *
	 * @return int
	 */
	public static function factorial($number)
	{
		return $number ? $number * math::factorial($number - 1) : 1;
	}

	public static function fibonacci($number)
	{
		return round(pow((sqrt(5) + 1) / 2, $number - 1) / sqrt(5));
	}

	/**
	 * @en Determine if a number is prime
	 * @ru Проверяет, является ли указанное число простым
	 *
	 * @param int $number
	 *
	 * @return bool
	 */
	public static function is_prime($number)
	{
		# 1 is not prime, see: http://en.wikipedia.org/wiki/Prime_number#Primality_of_one
		if ($number == 1)
		{
			return false;
		}

		# 2 is prime (the only even number that is prime)
		if ($number == 2)
		{
			return true;
		}

		/**
		 * if the number is divisible by two, then it's not prime and it's no longer
		 * needed to check other even numbers
		 */
		if ($number % 2 == 0)
		{
			return false;
		}

		/**
		 * Checks the odd numbers. If any of them is a factor, then it returns false.
		 * The sqrt can be an aproximation, hence just for the sake of
		 * security, one rounds it to the next highest integer value.
		 */
		for ($i = 3; $i <= ceil(sqrt($number)); $i = $i + 2)
		{
			if ($number % $i == 0)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * @en Generating prime numbers (sieve of Eratosthenes algorithm)
	 * @ru Генерация простых чисел массивом (метод решета Эратосфена)
	 *
	 * $result = math::primes(100); # Array
	 *                              # (
	 *                              #     [0] => 2
	 *                              #     [1] => 3
	 *                              #     [2] => 5
	 *                              #     [3] => 7
	 *                              #     [4] => 11
	 *                              #     [5] => 13
	 *                              #     [6] => 17
	 *                              #     [7] => 19
	 *                              #     [8] => 23
	 *                              #     [9] => 29
	 *                              #     [10] => 31
	 *                              #     [11] => 37
	 *                              #     [12] => 41
	 *                              #     [13] => 43
	 *                              #     [14] => 47
	 *                              #     [15] => 53
	 *                              #     [16] => 59
	 *                              #     [17] => 61
	 *                              #     [18] => 67
	 *                              #     [19] => 71
	 *                              #     [20] => 73
	 *                              #     [21] => 79
	 *                              #     [22] => 83
	 *                              #     [23] => 89
	 *                              #     [24] => 97
	 *                              # )
	 *
	 * @param int $limit устанавливает ограничение на поиск простых чисел
	 * @return array
	 */
	public static function primes($limit = 1000)
	{
		$numbers = array_fill(0, $limit, true);

		$numbers[0] = false;
		$numbers[1] = false;

		for ($i = 2; $i < $limit; $i++)
		{
			if ($numbers[$i])
			{
				for ($j = 2; $i * $j < $limit; $j++)
				{
					$numbers[$i * $j] = false;
				}
			}
		}


		$result = null;

		foreach ($numbers as $number => $is_prime)
		{
			if ($is_prime)
			{
				$result[] = $number;
			}
		}

		return $result;
	}
}