<?php

    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {

        	// $this->call(TipousuarioSeeder::class);
            // $this->call(InstituicaoSeeder::class);
            // $this->call(CicloSeeder::class);
            // $this->call(UnidadeAcademicaSeeder::class);
            // $this->call(CursoSeeder::class);
            // $this->call(DisciplinaSeeder::class);
            // $this->call(QuestaoSeeder::class);
            // $this->call(UsuarioSeeder::class);
	        // $this->call(AlunoSeeder::class);

            // $this->call(AddTipousuarioSeeder::class);
            // $this->call(UpdateLMTSSeeder::class);
            /* $this->call(InstituicaoSeeder::class); */
            // $this->call(UpdateUnidadeAcademicasSeeder::class);
            // $this->call(UpdateCicloSeeder::class);

            /* $this->call(UsuarioSeeder::class); */

            $this->call(DisciplinaUPESeeder::class);
        }
    }
