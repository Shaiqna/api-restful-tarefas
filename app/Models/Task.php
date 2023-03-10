<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Task"
 * )
*/

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'completed',
        'user_id'
    ];

    /**
     * @OA\Property(
     *     property="title",
     *     type="string",
     *     example="Minha primeira tarefa"
     * )
     *
     * @var string
     */

    public $title;

    /**
     * @OA\Property(
     *     property="description",
     *     type="string",
     *     example="Descrição da minha tarefa"
     * )
     *
     * @var string
     */

    public $description;

    /**
     * @OA\Property(
     *     property="completed",
     *     type="boolean",
     *     example=true
     * )
     *
     * @var boolean
     */

    public $completed;
    public $user_id;
}
