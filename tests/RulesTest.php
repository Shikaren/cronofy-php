<?php
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{

    public function testGetAvailabilityRule()
    {
        $expected_rule = array(
            "availability_rule" => array(
                "availability_rule_id" => "default",
                "tzid" => "Etc/UTC",
                "weekly_periods" => array(
                    array(
                        "day" => "monday",
                        "start_time" => "09:00",
                        "end_time" => "13:00"
                    ),
                    array(
                        "day" => "monday",
                        "start_time" => "14:00",
                        "end_time" => "17:00"
                    ),
                    array(
                        "day" => "tuesday",
                        "start_time" => "09:00",
                        "end_time" => "17:00"
                    )
                )
            )
        );
        $http = $this->createMock('HttpRequest');
        $http->expects($this->once())
            ->method('http_get')
            ->with(
                $this->equalTo('https://api.cronofy.com/v1/availability_rules/default')
            )
            ->will( $this->returnValue( array( json_encode($expected_rule), 200 ) ) );

        $cronofy = new Cronofy(array(
            "client_id" => "clientId",
            "client_secret" => "clientSecret",
            "access_token" => "accessToken",
            "refresh_token" => "refreshToken",
            "http_client" => $http,
        ));

        $response = $cronofy->get_availability_rule("default");
        
        $this->assertNotNull( $response );
        $this->assertEquals( 3, count( $response['availability_rule'] ) );
        $this->assertEquals( "default", $response['availability_rule']['availability_rule_id'] );
    }
    
    public function testListAvailabilityRules()
    {
        $expected_rules = array(
            "availability_rules" => array(
                array(
                    "availability_rule_id" => "default",
                    "tzid" => "Etc/UTC",
                    "weekly_periods" => array(
                        array(
                            "day" => "monday",
                            "start_time" => "09:00",
                            "end_time" => "13:00"
                        ),
                        array(
                            "day" => "monday",
                            "start_time" => "14:00",
                            "end_time" => "17:00"
                        ),
                        array(
                            "day" => "tuesday",
                            "start_time" => "09:00",
                            "end_time" => "17:00"
                        )
                    )
                ),
                array(
                    "availability_rule_id" => "work_hours",
                    "tzid" => "Etc/UTC",
                    "weekly_periods" => array(
                        array(
                            "day" => "tuesday",
                            "start_time" => "09:00",
                            "end_time" => "17:00"
                        ),
                        array(
                            "day" => "wednesday",
                            "start_time" => "09:00",
                            "end_time" => "17:00"
                        )
                    )
                ),
            )
        );
        $http = $this->createMock('HttpRequest');
        $http->expects($this->once())
            ->method('http_get')
            ->with(
                $this->equalTo('https://api.cronofy.com/v1/availability_rules')
            )
            ->will( $this->returnValue( array( json_encode($expected_rules), 200 ) ) );

        $cronofy = new Cronofy(array(
            "client_id" => "clientId",
            "client_secret" => "clientSecret",
            "access_token" => "accessToken",
            "refresh_token" => "refreshToken",
            "http_client" => $http,
        ));

        $response = $cronofy->list_availability_rules();
        
        $this->assertNotNull( $response );
        $this->assertEquals( 2, count( $response['availability_rules'] ) );
        $this->assertEquals( 3, count( $response['availability_rules'][0] ) );
        $this->assertEquals( "default", $response['availability_rules'][0]['availability_rule_id'] );
        $this->assertEquals( "work_hours", $response['availability_rules'][1]['availability_rule_id'] );
    }
}
