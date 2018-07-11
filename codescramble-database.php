<?php

function get_code_from_database() {
	// Hard-coded database. Heh.
	$tasks = [];

	$tasks[0] = [
		'description' => "Write a program that asks the user for their name and then greets them by name. The user can type anything they want, but can't leave it blank.",
		'code' => new ArrayObject([
			"def get_a_name():",
			"    name = ''",
			"    while name == '':",
			"        name = input(\"What's your name? \")",
			"        name = name.strip()",
			"    return name",
			"your_name = get_a_name()",
			"print('Hello,', your_name)",
		]),
	];
	$tasks[1] = [
		'description' => "Write a function to combine/interleave two lists. Use a couple of small lists to test the function by printing the results of combining the two lists.",
		'code' => new ArrayObject([
			"def zip(list1, list2):",
			"    newlist = []",
			"    for i in range(len(list1)):",
			"        newlist.append(list1[i])",
			"        newlist.append(list2[i])",
			"    return newlist",
			"firsts = ['Amy', 'Debbie', 'Emma']",
			"lasts = ['Alkon', 'Goddard', 'Thompson']",
			"combined = zip(firsts, lasts)",
			"print(combined)",
		]),
	];

	$tasks[2] = [
		'description' => 'Write a function that accepts a list and returns a list containing the 3 largest numbers in it in ascending order. Test it by calling it several times with some lists of numbers and printing the results.',
		'code' => new ArrayObject([
			"def top_three(some_numbers):",
			"    high1 = max(some_numbers)",
			"    some_numbers.remove(high1)",
			"    high2 = max(some_numbers)",
			"    some_numbers.remove(high2)",
			"    high3 = max(some_numbers)",
			"    return [high3, high2, high1]",
			"print(top_three([10, 1, 8, 3, 6, 4]))",
			"print(top_three([69, 42, 27]))",
		]),
	];

	return $tasks[rand(0, sizeof($tasks) - 1)];
}
