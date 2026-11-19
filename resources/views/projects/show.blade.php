<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:underline">← Back to Dashboard</a>
                <h2 class="font-bold text-2xl text-gray-800 mt-1">{{ $project->title }}</h2>
            </div>
            <span class="text-sm text-gray-500">
                Deadline: <strong>{{ $project->deadline ?? 'Not Set' }}</strong>
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add Task Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-3">+ Add New Task</h3>
                <form action="{{ route('tasks.store', $project) }}" method="POST" class="flex flex-col md:flex-row gap-3">
                    @csrf
                    <input type="text" name="title" placeholder="What needs to be done?" required
                        class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <select name="priority" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="low">Low Priority</option>
                        <option value="medium" selected>Medium Priority</option>
                        <option value="high">High Priority</option>
                    </select>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                        Add Task
                    </button>
                </form>
            </div>

            <!-- Task List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800">Project Tasks ({{ $project->tasks->count() }})</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($project->tasks as $task)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50">
                            <div class="flex items-center space-x-3">
                                @php
                                    $colors = [
                                        'high' => 'bg-red-100 text-red-700',
                                        'medium' => 'bg-yellow-100 text-yellow-700',
                                        'low' => 'bg-green-100 text-green-700',
                                    ];
                                @endphp
                                <span class="text-xs font-bold uppercase px-2.5 py-1 rounded {{ $colors[$task->priority] }}">
                                    {{ $task->priority }}
                                </span>

                                <span class="font-medium {{ $task->status === 'done' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                    {{ $task->title }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="text-sm rounded-lg border-gray-300 py-1
                                        {{ $task->status === 'done' ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                        <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done ✓</option>
                                    </select>
                                </form>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 text-sm">✕</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            No tasks in this project yet. Add one above!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
