<x-app-layout>

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            padding: 15px 15px;
            background: #399268;
            color: white;
            border-radius: 15px 15px 0 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .table th {
            width: 200px;
            background-color: #f1f3f5;
        }
        .attachments img {
            width: 150px;
            height: auto;
            border-radius: 10px;
            margin: 5px;
            border: 1px solid #dee2e6;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .attachments img:hover {
            transform: scale(1.05);
        }
    </style>


    <div class="min-h-auto flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-5xl mb-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Complaint Details</h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Complaint #
                            </th>
                            <td class="px-6 py-4">
                                {{ $complaint->complaint_no }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Complaint Time
                            </th>
                            <td class="px-6 py-4">
                                {{ date('d M h:i A', strtotime($complaint->complaint_at)) }}
                            </td>
                        </tr>

                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Name
                            </th>
                            <td class="px-6 py-4">
                                {{ $complaint->name }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Category
                            </th>
                            <td class="px-6 py-4">
                                {{ optional($complaint->category)->name }}
                            </td>
                        </tr>
                        
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                CNIC
                            </th>
                            <td class="px-6 py-4">
                                {{ addDashesInCNIC($complaint->cnic) }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Mobile
                            </th>
                            <td class="px-6 py-4">
                                {{ addDashInMobile($complaint->mobile) }}
                            </td>
                        </tr>
                        
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                Description
                            </th>
                            <td class="px-6 py-4" colspan="3" class="urduLabel">
                                {{ $complaint->description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
    </div>

                                                <tr>
                                                    <th>Location</th>
                                                    <td class="urduLabel">{{ $complaint->location }}</td>
                                                    <th>Source</th>
                                                    <td>{{ optional($complaint->source)->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Complaint By</th>
                                                    <td>{{ optional($complaint->complaint_by)->name }}</td>
                                                    <th>Complaint Status</th>
                                                    <td>{!! getComplaintStatusBadge($complaint) !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Attachments</th>
                                                    <td class="attachments" colspan="3">
                                                        @if($complaint->attachment)
                                                            @php
                                                                $ext = strtolower(pathinfo($complaint->attachment, PATHINFO_EXTENSION));
                                                                $videoExt = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                                                            @endphp
                                                            <a href="{{ asset('storage/complaints/' . $complaint->attachment) }}" target="_blank">
                                                                @if($ext === 'pdf')
                                                                    <img src="{{ asset('images/pdf_icon.png') }}" width="120" alt="PDF" />
                                                                @elseif(in_array($ext, $videoExt))
                                                                    <img src="{{ asset('images/vlc_icon.png') }}" width="120" alt="PDF" />
                                                                @else
                                                                    <img src="{{ asset('storage/complaints/' . $complaint->attachment) }}" width="120" />
                                                                @endif
                                                            </a>
                                                        @endif

                                                        @if($complaint->complaint_status == 1 && $complaint->resolved_attachment)
                                                            @php
                                                                $resolvedExt = strtolower(pathinfo($complaint->resolved_attachment, PATHINFO_EXTENSION));
                                                                $videoExt = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
                                                            @endphp
                                                            <a href="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" target="_blank" class="ms-5">
                                                                @if($resolvedExt === 'pdf')
                                                                    <img src="{{ asset('images/pdf_icon.png') }}" width="120" alt="PDF" />
                                                                @elseif(in_array($resolvedExt, $videoExt))
                                                                    <img src="{{ asset('images/vlc_icon.png') }}" width="120" alt="PDF" />
                                                                @else
                                                                    <img src="{{ asset('storage/complaints/' . $complaint->resolved_attachment) }}" width="120" />
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($complaint->department_id > 0)
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{ optional($complaint->department)->name }}</td>
                                                    <th>Assigned By</th>
                                                    <td>{{ optional($complaint->assigned_user)->name }} 
                                                        @if($complaint->assigned_at)
                                                        ({{ date('d M h:i A', strtotime($complaint->assigned_at)) }})
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif

                                                @if($complaint->complaint_status == 1 || $complaint->complaint_status == 3)
                                                <tr>
                                                    <th>Resolved By</th>
                                                    <td>{{ optional($complaint->resolved_user)->name }}</td>
                                                    <th>Resolved At</th>
                                                    <td>{{ date('d M h:i A', strtotime($complaint->resolved_at)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Remarks</th>
                                                    <td colspan="3" class="urduLabel">{{ $complaint->remarks }}</td>
                                                </tr>
                                                @endif

                                                @if($complaint->complaint_status == 3)
                                                <tr>
                                                    <th>Reopened By</th>
                                                    <td>{{ optional($complaint->reopened_user)->name }}</td>
                                                    <th>Reopened Remarks</th>
                                                    <td class="urduLabel">{{ $complaint->reopened_remarks }}</td>
                                                </tr>
                                                @endif

                                            </tbody>
                                            </table>
                                        </div>

                                    </div>
                                <!-- [ basic-table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

</x-app-layout>