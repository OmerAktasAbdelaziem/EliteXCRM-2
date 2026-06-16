<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\UserPermission;
use App\Http\Services\Question\Interfaces\QuestionServiceInterface;
use App\Models\ClientQuestion;

class ClientQuestionController extends Controller {

    protected QuestionServiceInterface $questionService;

    public function __construct(QuestionServiceInterface $questionService) {
        $this->questionService = $questionService;
    }

    public function index(Request $request) {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);

        
        $questions = $this->questionService->getByFilters([]);

        return view('question.index', compact(
            'isSuperAdmin',
            'userAuth',
            'questions',
        ));
    }

    public function create() {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);

        $question = new ClientQuestion();

        return view('question.show', compact(
            'isSuperAdmin',
            'userAuth',
            'question',
        ));
    }

    public function store(Request $request) {
        $inputs = $request->only([
            'question_text',
            'is_text',
        ]);

        $question = $this->questionService->create($inputs)->first();
    
        return redirect()->route('question.show', $question->id)->with('success', 'Question Created Successfully');
    }

    public function show($id) {
        $userAuth = Auth::user();
        $isSuperAdmin = UserPermission::isSuperAdmin($userAuth);
        
        $question = $this->questionService->getById($id)->first();

        return view('question.show', compact(
            'question',
            'userAuth',
            'isSuperAdmin',
        ));
    }

    public function update(Request $request, $id) {     
        $inputs = $request->only([
            'question_text',
            'is_text',
        ]);
     
        $this->questionService->update($id, $inputs);

        return redirect()->back()->with('success', 'Question Updated Successfully');
    }

    public function delete($id) {
        $this->questionService->deleteByParams(['id' => $id]);
        return redirect()->route('question.index')->with('success', 'Question Deleted Successfully');
    }

}
