<?php
class DepartmentSeeder extends Seeder {
    public function run()
    {
        department::truncate();
        $departments = ['General Management',
		'business strategies',
		'Marketing Department',
		'Operations Department',
		'Finance Department',
		'Sales Department',
		'Human Resource Department',
	    'Purchase Department'
		];
        foreach ($departments as $key => $value) {
            department::create([
                'name' => $value
            ]);
        }
    }
}
