<?php

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private $baseUrl = 'http://localhost:8001';

    public function testCreateUserAndListUsers()
    {
        // Generate fake user data
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User',
            'phone_number' => '555-1234',
        ];

        // Make a POST request to the create_user endpoint
        $headers = [
            'Content-Type: application/json',
        ];
        $response = $this->makeRequest('create_user', 'POST', json_encode($data), $headers);

        // Check the response status code
        $this->assertEquals(200, $response['status']);

        // Make a GET request to the list_users endpoint
        $response = $this->makeRequest('list_users', 'GET');

        // Check the response status code
        $this->assertEquals(200, $response['status']);

        // Parse the JSON response
        $users = json_decode($response['body'], true);

        // Check that the list of users contains exactly one item
        $this->assertCount(1, $users);
        $this->assertEquals('test@example.com', $users[0]['email']);
        $this->assertEquals('Test User', $users[0]['name']);
        $this->assertEquals('555-1234', $users[0]['phone_number']);
    }

    private function makeRequest($endpoint, $method = 'GET', $data = null, $headers = [])
    {
        $url = $this->baseUrl . '/' . $endpoint;
        // Use the makeRequest function from the previous step to make the request
        return makeRequest($url, $method, $data, $headers);
    }
}