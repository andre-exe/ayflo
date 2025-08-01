<?php

return [

    'connection' => env('DB_CONNECTION', 'pgsql'),

    'default_namespace' => 'App\\Models',

    '*' => [

        /*
        |--------------------------------------------------------------------------
        | Model Files Location
        |--------------------------------------------------------------------------
        |
        | We need a location to store your new generated files. All files will be
        | placed within this directory. When you turn on base files, they will
        | be placed within a Base directory inside this location.
        |
        */

        'path' => app_path('Models'),

        /*
        |--------------------------------------------------------------------------
        | Model Namespace
        |--------------------------------------------------------------------------
        |
        | Every generated model will belong to this namespace. It is suggested
        | that this namespace should follow PSR-4 convention and be very
        | similar to the path of your models defined above.
        |
        */

        'namespace' => 'App\Models',

        /*
        |--------------------------------------------------------------------------
        | Parent Class
        |--------------------------------------------------------------------------
        |
        | All Eloquent models should inherit from Eloquent Model class. However,
        | you can define a custom Eloquent model that suits your needs.
        | As an example one custom model has been added for you which
        | will allow you to create custom database castings.
        |
        */

        'parent' => Illuminate\Database\Eloquent\Model::class,

        /*
        |--------------------------------------------------------------------------
        | Traits
        |--------------------------------------------------------------------------
        |
        | Sometimes you may want to append certain traits to all your models.
        | If that is what you need, you may list them bellow.
        | As an example we have a BitBooleans trait which will treat MySQL bit
        | data type as booleans. You might probably not need it, but it is
        | an example of how you can customize your models.
        |
        */

        'use' => [
            // Reliese\Database\Eloquent\BitBooleans::class,
            // Reliese\Database\Eloquent\BlamableBehavior::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Model Connection
        |--------------------------------------------------------------------------
        |
        | If you wish your models had appended the connection from which they
        | were generated, you should set this value to true and your
        | models will have the connection property filled.
        |
        */

        'connection' => false,

        /*
        |--------------------------------------------------------------------------
        | Timestamps
        |--------------------------------------------------------------------------
        |
        | If your tables have CREATED_AT and UPDATED_AT timestamps you may
        | enable them and your models will fill their values as needed.
        | You can also specify which fields should be treated as timestamps
        | in case you don't follow the naming convention Eloquent uses.
        | If your table doesn't have these fields, timestamps will be
        | disabled for your model.
        |
        */

        'timestamps' => true,

        // 'timestamps' => [
        //     'enabled' => true,
        //     'fields' => [
        //         'CREATED_AT' => 'created_at',
        //         'UPDATED_AT' => 'updated_at',
        //     ]
        // ],

        /*
        |--------------------------------------------------------------------------
        | Soft Deletes
        |--------------------------------------------------------------------------
        |
        | If your tables support soft deletes with a DELETED_AT attribute,
        | you can enable them here. You can also specify which field
        | should be treated as a soft delete attribute in case you
        | don't follow the naming convention Eloquent uses.
        | If your table doesn't have this field, soft deletes will be
        | disabled for your model.
        |
        */

        'soft_deletes' => true,

        // 'soft_deletes' => [
        //     'enabled' => true,
        //     'field' => 'deleted_at',
        // ],

        /*
        |--------------------------------------------------------------------------
        | Date Format
        |--------------------------------------------------------------------------
        |
        | Here you may define your models' date format. The following format
        | is the default format Eloquent uses. You won't see it in your
        | models unless you change it to a more convenient value.
        |
        */

        'date_format' => 'Y-m-d H:i:s',

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        |
        | Here you may define how many models Eloquent should display when
        | paginating them. The default number is 15, so you might not
        | see this number in your models unless you change it.
        |
        */

        'per_page' => 15,

        /*
        |--------------------------------------------------------------------------
        | Base Files
        |--------------------------------------------------------------------------
        |
        | By default, your models will be generated in your models path, but
        | when you generate them again they will be replaced by new ones.
        | You may want to customize your models and, at the same time, be
        | able to generate them as your tables change. For that, you
        | can enable base files. These files will be replaced whenever
        | you generate them, but your customized files will not be touched.
        |
        */

        'base_files' => false,

        /*
        |--------------------------------------------------------------------------
        | Snake Attributes
        |--------------------------------------------------------------------------
        |
        | Eloquent treats your model attributes as snake cased attributes, but
        | if you have camel-cased fields in your database you can disable
        | that behaviour and use camel case attributes in your models.
        |
        */

        'snake_attributes' => true,

        /*
        |--------------------------------------------------------------------------
        | Indent options
        |--------------------------------------------------------------------------
        |
        | As default indention is done with tabs, but you can change it by setting
        | this to the amount of spaces you that you want to use for indentation.
        | Usually you will use 4 spaces instead of tabs.
        |
        */

        'indent_with_space' => 0,

        /*
        |--------------------------------------------------------------------------
        | Qualified Table Names
        |--------------------------------------------------------------------------
        |
        | If some of your tables have cross-database relationships (probably in
        | MySQL), you can make sure your models take into account their
        | respective database schema.
        |
        | Can Either be NULL, FALSE or TRUE
        | TRUE: Schema name will be prepended on the table
        | FALSE:Table name will be set without schema name.
        | NULL: Table name will follow laravel pattern,
        |   i.e. if class name(plural) matches table name, then table name will not be added
        */

        'qualified_tables' => false,

        /*
        |--------------------------------------------------------------------------
        | Hidden Attributes
        |--------------------------------------------------------------------------
        |
        | When casting your models into arrays or json, the need to hide some
        | attributes sometimes arise. If your tables have some fields you
        | want to hide, you can define them bellow.
        | Some fields were defined for you.
        |
        */

        'hidden' => [
            '*secret*', '*password', '*token',
        ],

        /*
        |--------------------------------------------------------------------------
        | Mass Assignment Guarded Attributes
        |--------------------------------------------------------------------------
        |
        | You may want to protect some fields from mass assignment. You can
        | define them bellow. Some fields were defined for you.
        | Your fillable attributes will be those which are not in the list
        | excluding your models' primary keys.
        |
        */

        'guarded' => [
            // 'created_by', 'updated_by'
        ],

        /*
        |--------------------------------------------------------------------------
        | Casts
        |--------------------------------------------------------------------------
        |
        | You may want to specify which of your table fields should be cast as
        | something other than a string. For instance, you may want a
        | text field be cast as an array or and object.
        |
        | You may define column patterns which will be cast using the value
        | assigned. We have defined some fields for you. Feel free to
        | modify them to fit your needs.
        |
        */

        'casts' => [
            '*_json' => 'json',
        ],

        /*
        |--------------------------------------------------------------------------
        | Excluded Tables
        |--------------------------------------------------------------------------
        |
        | When performing the generation of models you may want to skip some of
        | them, because you don't want a model for them or any other reason.
        | You can define those tables bellow. The migrations table was
        | filled for you, since you may not want a model for it.
        |
        */

        'except' => [
            'migrations',
            'failed_jobs',
            'password_resets',
            'personal_access_tokens',
            'password_reset_tokens',
        ],

        /*
        |--------------------------------------------------------------------------
        | Specified Tables
        |--------------------------------------------------------------------------
        |
        | You can specify specific tables. This will generate the models only
        | for selected tables, ignoring the rest.
        |
        */

        'only' => [
            // 'users',
        ],

        /*
        |--------------------------------------------------------------------------
        | Table Prefix
        |--------------------------------------------------------------------------
        |
        | If you have a prefix on your table names but don't want it in the model
        | and relation names, specify it here.
        |
        */

        'table_prefix' => '',

        /*
        |--------------------------------------------------------------------------
        | Lower table name before doing studly
        |--------------------------------------------------------------------------
        |
        | If tables names are capitalised using studly produces incorrect name
        | this can help fix it ie TABLE_NAME now becomes TableName
        |
        */

        'lower_table_name_first' => false,

        /*
        |--------------------------------------------------------------------------
        | Model Names
        |--------------------------------------------------------------------------
        |
        | By default the generator will create models with names that match your tables.
        | However, if you wish to manually override the naming, you can specify a mapping
        | here between table and model names.
        |
        | Example:
        |   A table called 'billing_invoices' will generate a model called `BillingInvoice`,
        |   but you'd prefer it to generate a model called 'Invoice'. Therefore, you'd add
        |   the following array key and value:
        |     'billing_invoices' => 'Invoice',
        */

        'model_names' => [

        ],

        /*
        |--------------------------------------------------------------------------
        | Relation Name Strategy
        |--------------------------------------------------------------------------
        |
        | How the relations should be named in your models.
        |
        | 'related'     Use the related table as the relation name.
        |               (post.author --> user.id)
                            generates Post::user() and User::posts()
        |
        | 'foreign_key' Use the foreign key as the relation name.
        |               This can help to provide more meaningful relationship names, and avoids naming conflicts
        |               if you have more than one relationship between two tables.
        |                   (post.author_id --> user.id)
        |                       generates Post::author() and User::posts_where_author()
        |                   (post.editor_id --> user.id)
        |                       generates Post::editor() and User::posts_where_editor()
        |               ID suffixes can be omitted from foreign keys.
        |                   (post.author --> user.id)
        |                   (post.editor --> user.id)
        |                       generates the same as above.
        |               Where the foreign key matches the related table name, it behaves as per the 'related' strategy.
        |                   (post.user_id --> user.id)
        |                       generates Post::user() and User::posts()
        */

        'relation_name_strategy' => 'related',
        // 'relation_name_strategy' => 'foreign_key',

        /*
         |--------------------------------------------------------------------------
         | Determines need or not to generate constants with properties names like
         |
         | ...
         | const AGE = 'age';
         | const USER_NAME = 'user_name';
         | ...
         |
         | that later can be used in QueryBuilder like
         |
         | ...
         | $builder->select([User::USER_NAME])->where(User::AGE, '<=', 18);
         | ...
         |
         | that helps to avoid typos in strings when typing field names and allows to use
         | code competition with available model's field names.
         */
        'with_property_constants' => false,

        /*
         |--------------------------------------------------------------------------
         | Optionally includes a full list of columns in the base generated models,
         | which can be used to avoid making calls like
         |
         | ...
         | \Illuminate\Support\Facades\Schema::getColumnListing
         | ...
         |
         | which can be slow, especially for large tables.
         */
        'with_column_list' => false,

        /*
        |--------------------------------------------------------------------------
        | Disable Pluralization Name
        |--------------------------------------------------------------------------
        |
        | You can disable pluralization tables and relations
        |
        */
        'pluralize' => true,

        /*
        |--------------------------------------------------------------------------
        | Disable Pluralization Except For Certain Tables
        |--------------------------------------------------------------------------
        |
        | You can enable pluralization for certain tables
        |
        */
        'override_pluralize_for' => [

        ],

        /*
        |--------------------------------------------------------------------------
        | Move $hidden property to base files
        |--------------------------------------------------------------------------
        | When base_files is true you can set hidden_in_base_files to true
        | if you want the $hidden to be generated in base files
        |
        */
        'hidden_in_base_files' => false,

        /*
        |--------------------------------------------------------------------------
        | Move $fillable property to base files
        |--------------------------------------------------------------------------
        | When base_files is true you can set fillable_in_base_files to true
        | if you want the $fillable to be generated in base files
        |
        */
        'fillable_in_base_files' => false,

        /*
        |--------------------------------------------------------------------------
        | Generate return types for relation methods.
        |--------------------------------------------------------------------------
        | When enable_return_types is set to true, return type declarations are added
        | to all generated relation methods for your models.
        |
        | NOTE: This requires PHP 7.0 or later.
        |
        */
        'enable_return_types' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Specifics
    |--------------------------------------------------------------------------
    |
    | In this section you may define the default configuration for each model
    | that will be generated from a specific database. You can also nest
    | table specific configurations.
    | These values will override those defined in the section above.
    |
    */

    // 'shop' => [
    //     'path' => app_path(),
    //     'namespace' => 'App',
    //     'snake_attributes' => false,
    //     'qualified_tables' => true,
    //     'use' => [
    //         Reliese\Database\Eloquent\BitBooleans::class,
    //     ],
    //     'except' => ['migrations'],
    //     'only' => ['users'],
    //      // Table Specifics Bellow:
    //     'user' => [
    //      // Don't use any default trait
    //         'use' => [],
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | Connection Specifics
    |--------------------------------------------------------------------------
    |
    | In this section you may define the default configuration for each model
    | that will be generated from a specific connection. You can also nest
    | database and table specific configurations.
    |
    | You may wish to use connection specific config for setting a parent
    | model with a read only setup, or enforcing a different set of rules
    | for a connection, e.g. using snake_case naming over CamelCase naming.
    |
    | This supports nesting with the following key configuration values, in
    | reverse precedence order (i.e. the last one found becomes the value).
    |
    |       connections.{connection_name}.property
    |       connections.{connection_name}.{database_name}.property
    |       connections.{connection_name}.{table_name}.property
    |       connections.{connection_name}.{database_name}.{table_name}.property
    |
    | These values will override those defined in the section above.
    |
    */

//    'connections' => [
//        'read_only_external' => [
//            'parent' => \App\Models\ReadOnlyModel::class,
//            'connection' => true,
//            'users' => [
//                'connection' => false,
//            ],
//            'my_other_database' => [
//                'password_resets' => [
//                    'connection' => false,
//                ]
//            ]
//        ],
//    ],
];
