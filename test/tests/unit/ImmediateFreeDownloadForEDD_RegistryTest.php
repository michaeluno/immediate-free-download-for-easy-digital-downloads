<?php

/*
 $this->assertEquals()
$this->assertContains()
$this->assertFalse()
$this->assertTrue()
$this->assertNull()
$this->assertEmpty()
*/

class ImmediateFreeDownloadForEDD_RegistryTest extends \Codeception\Test\Unit {

    public function testGetPluginURL() {

    }

    public function testSetAdminNotice() {

    }

    public function testSetUp() {

        ImmediateFreeDownloadForEDD_Registry::$sDirPath = '';

        ImmediateFreeDownloadForEDD_Registry::setUp();
        $this->assertEquals(
            dirname( ImmediateFreeDownloadForEDD_Registry::$sFilePath ),
            ImmediateFreeDownloadForEDD_Registry::$sDirPath
        );

    }

    public function testReplyToShowAdminNotices() {

    }

    public function testRegisterClasses() {

        $_aClassFiles = $this->getStaticAttribute( 'ImmediateFreeDownloadForEDD_Registry', '___aAutoLoadClasses' );
        ImmediateFreeDownloadForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertAttributeEquals( $_aClassFiles , '___aAutoLoadClasses', 'ImmediateFreeDownloadForEDD_Registry' );

        $_aClassFiles = array( 'SomeClass' => 'SomeClass.php' );
        ImmediateFreeDownloadForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertAttributeNotEquals(
            $_aClassFiles ,
            '___aAutoLoadClasses',
            'ImmediateFreeDownloadForEDD_Registry'
        );

        $this->assertArrayHasKey(
            'SomeClass',
            $this->getStaticAttribute( 'ImmediateFreeDownloadForEDD_Registry', '___aAutoLoadClasses' ),
            'The key just set does not exist.'
        );

    }

    public function testReplyToLoadClass() {

        $this->assertFalse(
            class_exists( 'JustAClass' ),
            'The JustAClass class must not exist at this stage.'
        );
        include( codecept_root_dir() . '/tests/include/class-list.php' );
        ImmediateFreeDownloadForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertTrue(
            class_exists( 'JustAClass' ),
            'The class auto load failed with the ImmediateFreeDownloadForEDD_Registry::registerClasses() method.'
        );

    }

}
