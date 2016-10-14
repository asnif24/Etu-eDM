#!/usr/bin/env python
import sys

def main():
	if len(sys.argv) != 4:
		sys.exit(1)

	company_name, task_name, zip_path = sys.argv[1], sys.argv[2], sys.argv[3]
	print company_name, task_name, zip_path
	output = {'status':'SUCCESS', 'task_id':'task_1234567890'}
	print output
	return output

if __name__ == '__main__':
	output = main()
	# return output