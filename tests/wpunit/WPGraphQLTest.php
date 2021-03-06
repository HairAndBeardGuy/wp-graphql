<?php

class WPGraphQLTest extends \Codeception\TestCase\WPTestCase {

	public $instance;

	public function setUp(): void {
		parent::setUp();

		$this->instance = graphql_init();
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Ensure that graphql_init() returns an instance of WPGraphQL
	 *
	 * @covers WPGraphQL::instance()
	 */
	public function testInstance() {
		$this->assertTrue( $this->instance instanceof WPGraphQL );
	}

	/**
	 * @covers WPGraphQL::__wakeup()
	 * @covers WPGraphQL::__clone()
	 */
	public function testCloneWPGraphQL() {
		$rc = new ReflectionClass( $this->instance );
		$this->assertTrue( $rc->hasMethod( '__clone' ) );
		$this->assertTrue( $rc->hasMethod( '__wakeup' ) );
	}

	/**
	 * @covers WPGraphQL::setup_constants()
	 */
	public function testSetupConstants() {
		do_action( 'init' );
		$this->assertTrue( defined( 'WPGRAPHQL_VERSION' ) );
		$this->assertTrue( defined( 'WPGRAPHQL_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'WPGRAPHQL_PLUGIN_URL' ) );
		$this->assertTrue( defined( 'WPGRAPHQL_PLUGIN_FILE' ) );
	}

	/**
	 * @covers WPGraphQL::filters()
	 */
	public function testFilters() {

		global $wp_filter;
		graphql_init();
		$this->assertTrue( isset( $wp_filter['graphql_get_type']->callbacks ) );

	}

	/**
	 * @covers WPGraphQL::get_static_schema()
	 */
	public function testGetStaticSchema() {

		/**
		 * Set the file path for where to save the static schema
		 */
		$file_path = WPGRAPHQL_PLUGIN_DIR . 'schema.graphql';
		$contents  = 'test';
		file_put_contents( $file_path, $contents );
		$this->assertFileExists( $file_path );
		$static_schema = WPGraphQL::get_static_schema();
		$this->assertEquals( $contents, $static_schema );

	}

}
