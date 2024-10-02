<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur'],
            'Arunachal Pradesh' => ['Itanagar', 'Naharlagun'],
            'Assam' => ['Guwahati', 'Dibrugarh', 'Silchar'],
            'Bihar' => ['Patna', 'Gaya', 'Bhagalpur'],
            'Chhattisgarh' => ['Raipur', 'Bilaspur', 'Durg'],
            'Goa' => ['Panaji', 'Margao', 'Vasco da Gama'],
            'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara'],
            'Haryana' => ['Gurgaon', 'Faridabad', 'Panipat'],
            'Himachal Pradesh' => ['Shimla', 'Dharamshala', 'Mandi'],
            'Jharkhand' => ['Ranchi', 'Jamshedpur', 'Dhanbad'],
            'Karnataka' => ['Bengaluru', 'Mysuru', 'Mangaluru'],
            'Kerala' => ['Thiruvananthapuram', 'Kochi', 'Kozhikode'],
            'Madhya Pradesh' => ['Bhopal', 'Indore', 'Gwalior'],
            'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur'],
            'Manipur' => ['Imphal'],
            'Meghalaya' => ['Shillong'],
            'Mizoram' => ['Aizawl'],
            'Nagaland' => ['Kohima', 'Dimapur'],
            'Odisha' => ['Bhubaneswar', 'Cuttack', 'Rourkela'],
            'Punjab' => ['Chandigarh', 'Amritsar', 'Ludhiana'],
            'Rajasthan' => ['Jaipur', 'Jodhpur', 'Udaipur'],
            'Sikkim' => ['Gangtok'],
            'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai'],
            'Telangana' => ['Hyderabad', 'Warangal', 'Nizamabad'],
            'Tripura' => ['Agartala'],
            'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Varanasi'],
            'Uttarakhand' => ['Dehradun', 'Haridwar', 'Nainital'],
            'West Bengal' => ['Kolkata', 'Darjeeling', 'Siliguri'],
        ];

        foreach ($cities as $stateName => $cityList) {
            $state = State::where('name', $stateName)->first();

            foreach ($cityList as $cityName) {
                City::create([
                    'name' => $cityName,
                    'state_id' => $state->id,
                ]);
            }
        }
    }
}
