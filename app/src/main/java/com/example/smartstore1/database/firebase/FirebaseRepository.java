package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import java.util.List;

public interface FirebaseRepository<T> {
    Task<Void> add(T item);
    Task<Void> update(String id, T item);
    Task<Void> delete(String id);
    Task<T> get(String id);
    Task<List<T>> getAll();
}
