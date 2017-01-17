<?php
use Migrations\AbstractMigration;

class CreateBookmarksTags extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('bookmarks_tags', [
            'id' => false,
            'primary_key' => ['bookmark_id', 'tag_id']
        ]);
        $table->addColumn('bookmark_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('tag_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addForeignKey('tag_id', 'tags', 'id');
        $table->addForeignKey('bookmark_id', 'bookmarks', 'id');
        $table->create();
    }
}
