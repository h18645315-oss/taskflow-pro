<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('TaskFlow Pro — Project Workspace') }}
            </h2>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                Total Projects: {{ $projects->count() }}
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Success Message Alert -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Create New Project Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">+ Create New Project</h3>
                <form action="{{ route('projects.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <input type="text" name="title" placeholder="Project Name (e.g. E-Commerce API)" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="text" name="description" placeholder="Short Description..."
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="date" name="deadline"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($projects as $project)
                    @php
                        $progress = $project->tasks_count > 0
                            ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                            : 0;
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="text-xl font-bold text-gray-900">{{ $project->title }}</h4>
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-blue-100 text-blue-800 uppercase">
                                    {{ $project->status }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                                {{ $project->description ?? 'No description provided.' }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mt-5">
                                <div class="flex justify-between text-xs font-medium text-gray-500 mb-1">
                                    <span>Progress ({{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks)</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-400">
                                Due: {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M, Y') : 'No deadline' }}
                            </span>
                            <div class="flex space-x-3">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                    Open Board →
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500">No projects yet. Create your first project above!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
