
 <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{$model->getTypeFormatted($lang)}}</td>
												<td class="text-center">
													{{ $model->getAmountFormatted() }}
												</td>
                                                <td class="text-center">{{$model->getModelTypeFormatted()}}</td>
                                                <td class="text-center">{{$model->getUserName()}}</td>
                                                <td class="text-center">{{$model->getUserPhone()}}</td>
                                                <td class="text-center">{{$model->getNote($lang)}}</td>
                                                <td class="text-center">{{$model->getCreatedAtFormatted()}}</td>
                                               
                                            
                                            </tr>
